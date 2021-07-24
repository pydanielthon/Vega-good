<?php

namespace App\Http\Controllers;
use App\Models\Contrahents;
use App\Models\Workers;
use App\Models\Hours;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\View\View;
use PDF;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ContrahentsController extends Controller
{

    public function index()
    {
        $user = User::find(Auth::id());

        if ($user->hasPermissionTo('Dostep do kontrahentow')) {

        } else {
            return redirect()->route('home');
        }
        $contrahents = Contrahents::all()->sortByDesc('status');
        $hours = DB::table('workers')
        ->join('hours', 'workers.id' ,'=', 'hours.workers_id')
        ->join('contrahents', 'contrahents.id', '=', 'hours.contrahents_id')
        ->select('contrahents.name', 'work_day', 'workers.status', 'contrahents.id')
        ->get();
        $dt = Carbon::now()->format('Y-m-d');

        $month = date('m');
        $day = date('d');
        $year = date('Y');
        $today = $year . '-' . $month . '-' . $day;
        $today = strtotime($today);
        $today = strtotime($today);
        foreach($hours as $hour){
            $hours = DB::table('contrahents')
            ->join('hours', $hour->id ,'=', 'hours.contrahents_id')
            ->max('work_day');
            $diff_in_days = ($today - strtotime($hours)) / 86400;
            if($diff_in_days > 14 ){
                $workeR = Contrahents::find($hour->id);
                $workeR->statusclick = 0;
                $workeR->save();
            }
            else if ($diff_in_days <= 14 ){
                $workeR = Contrahents::find($hour->id);

                $workeR->statusclick = 1;
                $workeR->save();
            }

        }


        return view('contrahents.list', ['contrahents' => $contrahents]);
    }


    public function create()
    {
        return view('contrahents.create');

    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);


        $cotnrahent = Contrahents::create($request->all());
        $user = User::find(Auth::id());
        DB::table('logs')->insert([
            [
            'name'=>'Dodanie kontrahenta',
            'contrahent_id'=>$cotnrahent->id,
            'created_at' =>date('d-m-Y H:i'),
            'user_id' =>  $user->id,
            ],
        ]);
        return redirect('/contrahents')->with('status', ' Kontrahent dodany');
    }


    public function show(int $contrahentsID, Request $request)
    {
        $user = User::find(Auth::id());

        if ($user->hasPermissionTo('Zobacz kontrahenta')) {

        } else {
            return redirect()->route('home');
        }
        $hasPerm=  User::find(Auth::id())->hasPermissionTo('Edytuj/usun godziny kontrahenta');

        $perm = Permission::all();
        $billed_json_before = DB::table('hours')
        ->where('hours.contrahents_id', '=', $contrahentsID)
        ->select( 'contrahent_billed_json')
        ->get();

        $contrahent = Contrahents::find($contrahentsID);

        $all_hours_array=[];
        $all_days_array = [];
        $all_billed_array =[];
        $all_hours = 0;



        $arrayLast = [];
        $hoursLast = DB::table('hours')
        ->where('hours.contrahents_id', '=', $contrahentsID)
        ->where('status_of_billings_contrahent', '=', 1)
        ->select( 'hours.hours', 'hours.work_day', 'hours.id')
        ->get();

        foreach($hoursLast as $hL){
            array_push($arrayLast, $hL->work_day);
        }

        if(count($arrayLast) > 0)
        {
            $lastBill = max($arrayLast);

        }
        else{
            $lastBill = "";
        }

        $days_from = "";
        $days_to = "";
        $day = date('d');
        $month = date('m');
        $year = date('Y');
        $today = $year . '-' . $month . '-' . $day;
        $workers = DB::table('hours')
            ->where('hours.contrahents_id', '=', $contrahentsID)
            ->select( 'hours.hours', 'hours.work_day', 'hours.id', 'status_of_billings_contrahent')
            ->get();
        if(count($workers) > 0)
        {
            foreach($workers as $worker)
            {
                array_push($all_hours_array, $worker->hours);
                array_push($all_days_array, $worker->work_day);
                if($worker->status_of_billings_contrahent == 0){
                    array_push($all_billed_array, $worker->hours);
                }

            }
            $all_hours = array_sum($all_hours_array);
            $days_from = min($all_days_array);
            $days_to = max($all_days_array);
        }


$worker_inputDateToPaid = $request->input('date');
$all_billed_hours = array_sum($all_billed_array);


        return view('contrahents.show',compact('contrahent', 'all_hours', 'days_from', 'days_to', 'today', 'workers', 'all_billed_hours', 'lastBill','hasPerm'));
    }


public function getPaidContr(int $contrahentsID, Request $request){
    $last_array =['1970-01-01'];
    $last = DB::table('hoursbill')
    ->where('contrahents_id', '=', $contrahentsID)
    ->select('hoursbill.date_to')
    ->get();


    foreach($last as $l)
    {
        array_push($last_array, $l->date_to);
    }
    $arr_last = array_values(array_slice($last_array, -1))[0];
    $arr_last = Carbon::createFromFormat('Y-m-d', $arr_last)->addDays(1)->format('d-m-Y');
    $amount = Contrahents::find($contrahentsID);

    $hours2 = DB::table('hours')
    ->where('contrahents_id', '=', $contrahentsID)
    ->where('hours.work_day', '>=',$arr_last)
    ->where('hours.work_day', '<=',$request->date)
    ->where('status_of_billings_contrahent', '=', 0 )
    ->sum('hours');

    $salary = $hours2 * $amount->salary_invoice;
 $insert = DB::table('hoursbill')
    ->insert([
        [
        'contrahents_id' =>$contrahentsID,
        'date_from' => $arr_last,
        'date_to' =>$request->date,
        'hours' => $hours2,
        'salary' =>$salary
    ],]);

$workers = DB::table('hours')
->where('hours.contrahents_id', '=', $contrahentsID)
->where('hours.work_day', '>=',$arr_last)
->where('hours.work_day', '<=',$request->date)
->select( 'hours.hours', 'hours.work_day', 'hours.id')
->update(['status_of_billings_contrahent' => 1 ]);

return back()->with('status','Rozliczono godziny kontrahenta');

}

public function addDay($value = 1)
{
    return $this->addDays($value);
}
    public function edit(int $contrahentID, Contrahents $contrahents)
    {
        $user = User::find(Auth::id());

        if ($user->hasPermissionTo('Edytuj kontrahenta')) {

        } else {
            return redirect()->route('home');
        }
        $contrahent = Contrahents::find($contrahentID);

        return view('contrahents.edit',compact('contrahents', 'contrahent'));

    }


    public function update(int $contrahentID, Request $request, Contrahents $contrahents)
    {


        $contrahent = Contrahents::find($contrahentID);


        $contrahent->name = $request->input('name');
        $contrahent->email = $request->input('email');
        $contrahent->salary_cash = $request->input('salary_cash');
        $contrahent->salary_invoice = $request->input('salary_invoice');
        $contrahent->notes = $request->input('notes');
        $contrahent->status = $request->input('status');

         $contrahent->save();
         $user = User::find(Auth::id());
         DB::table('logs')->insert([
             [
             'name'=>'Aktualizacja kontrahenta',
             'contrahent_id'=>$contrahents->id,
             'created_at' =>date('d-m-Y H:i'),
             'user_id' =>  $user->id,
             ],
         ]);
         return redirect('contrahents/'.$contrahent->id)->with('status','Zaktualizowano kontrahenta');

    }


    public function destroy(int $contrahentID, Contrahents $contrahents)
    {
        $contrahent = Contrahents::find($contrahentID);
        $contrahents->destroy($contrahentID);
        $user = User::find(Auth::id());
        DB::table('logs')->insert([
            [
            'name'=>'Usunięcie kontrahenta',
            'created_at' =>date('d-m-Y H:i'),
            'user_id' =>  $user->id,
            'notes' => $contrahent->name . " " . "(" . $contrahent->id . ")"
            ],
        ]);

        return redirect()->route('contrahents.index')->with('status', 'Usunięto kontrahenta');

    }
    public function deactivateWorker($id)
    {

        $contrahent = Contrahents::find($id);

        $contrahent->status = 0;
        $contrahent->save();
        $isHours = $contrahent->save();


        $user = User::find(Auth::id());



        if($contrahent->getChanges()){
            if($isHours){
                DB::table('logs')->insert([
                    [
                    'name'=>'Aktywacja kontrahenta',
                    'contrahent_id'=>$contrahent->contrahents_id,
                    'created_at' =>date('d-m-Y H:i'),
                    'user_id' =>  $user->id,
                ],
                ]);
            }
            return back()->with('status', 'Dezaktywowano kontrahenta');

        }else{
            return back()->with('status', 'Nie udało się dezaktywować kontrahenta');

        }

    }
    public function activateWorker($id)
    {
        $contrahent = Contrahents::find($id);

        $contrahent->status = 1;
        $isHours = $contrahent->save();
        $user = User::find(Auth::id());

        if($contrahent->getChanges()){
            if($isHours){
                DB::table('logs')->insert([
                    [
                    'name'=>'Deaktywacja kontrahenta',
                    'contrahent_id'=>$contrahent->contrahents_id,
                    'created_at' =>date('d-m-Y H:i'),
                    'user_id' =>  $user->id,
                ],
                ]);
            }
            return back()->with('status', 'Aktywowano kontrahenta');

        }else{
            return back()->with('status', 'Nie udało się aktywowwać kontrahenta');

        }

    }

    public function ajaxGetHoursFromContrahents(int $contrahentID, Request $request)
    {
        if($request->ajax)
        {
            if($request->contrahentsHoursFrom != '' && $request->contrahentsHoursTo != '')
            {
                $workers = DB::table('workers')
                ->whereBetween('work_day', array($request->contrahentsHoursFrom, $request->contrahentsHoursTo))
                ->join('hours', 'workers.id' ,'=', 'hours.workers_id')
                ->join('contrahents', 'contrahents.id', '=', 'hours.contrahents_id')
                ->where('hours.contrahents_id', '=', $contrahentID)
                ->select('workers.name', 'workers.surname', 'hours.hours', 'hours.work_day')
                ->get();
            }


        }
        $workers = DB::table('workers')
                                        ->join('hours', 'workers.id' ,'=', 'hours.workers_id')
                                        ->join('contrahents', 'contrahents.id', '=', 'hours.contrahents_id')
                                        ->where('hours.contrahents_id', '=', $contrahentID)
                                        ->select('workers.name', 'workers.surname', 'hours.hours', 'hours.work_day', 'hours.status_of_billings_contrahent', 'hours.id')

                                        ->get();

        return response ()->json( $workers );


    }


    public function ajaxGetPaidsFromContrahents(int $contrahentID, Request $request)
    {

    $bill = DB::table('hoursbill')
    ->where('contrahents_id', '=', $contrahentID)
    ->select('hoursbill.date_from', 'hoursbill.date_to', 'hours', 'salary', 'deposit', 'workers_id', 'id')

    ->get();
            return response ()->json(
                 [ [
                     'bill'=>$bill


               ]]
                );

    }



}