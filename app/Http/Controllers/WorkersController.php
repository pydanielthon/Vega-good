<?php

namespace App\Http\Controllers;

use App\Models\Workers;
use Spatie\Permission\Models\Role;
use App\Models\User;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Hours;
use App\Models\Hoursbill;
use Illuminate\Http\Response;

use Illuminate\Http\Request;
use Illuminate\View\View;
use PDF;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class WorkersController extends Controller
{
    public function index()
    {
        $user = User::find(Auth::id());

        if ($user->hasPermissionTo('Dostep do pracownikow')) {

        } else {
            return redirect()->route('home');
        }

        // return csrf_token();

        $month = date('m');
        $day = date('d');
        $year = date('Y');
        $today = $year . '-' . $month . '-' . $day;
        $today = strtotime($today);

        $workers = Workers::all()->sortByDesc("status");

        $maxDate = null;


        foreach($workers as $worker){
            $hours = DB::table('workers')
            ->join('hours', $worker->id ,'=', 'hours.workers_id')
            ->max('work_day');
            $diff_in_days = ($today - strtotime($hours)) / 86400;
            if($diff_in_days > 7 ){
                $workeR = Workers::find($worker->id);
                $workeR->statusclick = 0;
                $workeR->save();
            }
            else if ($diff_in_days <= 7 ){
                $workeR = Workers::find($worker->id);

                $workeR->statusclick = 1;
                $workeR->save();
            }

        }
        // foreach($workers as $worker)
        // {
        //     $w = Workers::find($worker->id)->hours;
        //     $arr_with_work_days = [];

        //     foreach($w as $ww){
        //         array_push($arr_with_work_days, $ww->work_day);
        //             $to = \Carbon\Carbon::createFromFormat('Y-m-d', $today);
        //             $from = \Carbon\Carbon::createFromFormat('Y-m-d', max($arr_with_work_days));
        //             $diff_in_days = $to->diffInDays($from);
        //                 $worker = Workers::find($worker->id);
        //                 if($diff_in_days > 7 and $worker->status == 1){
        //                 $worker->status = 0;
        //                 $worker->save();
        //         }
        //     }
        // }
        return view(
            'workers.list',
            [
                'workers' => $workers
            ]
        );
    }

    public function create()
    {
        return view('workers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'surname' => 'required',
        ]);


        $worker = Workers::create($request->all());
        $user = User::find(Auth::id());
        DB::table('logs')->insert([
            [
            'name'=>'Dodanie pracownika',
            'worker_id' =>$worker->id,
            'created_at' =>date('d-m-Y H:i'),
            'user_id' =>  $user->id,


        ],

        ]);
        return redirect('/workers')->with('status', ' Pracownik dodany');

    }

    public function show(int $workerID, Request $request)
    {

$user = User::find(Auth::id());

            if ($user->hasPermissionTo('Zobacz pracownika')) {

            } else {
                return redirect()->route('home');
            }
        $workerd = Workers::find($workerID);
        $hoursbill = Hoursbill::all();
        foreach($hoursbill as $hb){
            $sb = Hoursbill::find($hb->id);
        }
        $all_hours_array=[];
        $all_days_array = [];
        $all_hours = 0;
        $days_from = "";
        $days_to = "";
        $month = date('m');
        $day = date('d');
        $year = date('Y');


        $getLastArray = [];
        $getLast = DB::table('hours')
        ->where('hours.workers_id', '=', $workerID)
        ->select('hours.work_day', 'hours.status_of_billings_worker')
        ->get();
        foreach($getLast as $gL){
            array_push($getLastArray, $gL->work_day);
        }
        if(count($getLastArray) > 0)
        {
            $lastBill = max($getLastArray);

        }
        else{
            $lastBill = "";
        }

        $array_with_billings_dates['date'] = array();

        $array_date=[];

        $array_with_billings_dates['hours'][] = array();
        $billed_json_before = DB::table('hours')
        ->where('hours.workers_id', '=', $workerID)
        ->select( 'worker_billed_json')
        ->get();
        $today = $year . '-' . $month . '-' . $day;
        $workers = DB::table('hours')
            ->where('hours.workers_id', '=', $workerID)
            ->select( 'hours.hours', 'hours.work_day', 'hours.id', 'hours.status_of_billings_worker')
            ->get();
        if(count($workers) > 0)
            {
                foreach($workers as $worker)
                {
                    if($worker->status_of_billings_worker == 0)
                        {
                            array_push($all_hours_array, $worker->hours);
                            array_push($all_days_array, $worker->work_day);
                        }

                }
                if(!empty($all_days_array) and !empty($all_hours_array))
                {
                    $all_hours = array_sum($all_hours_array);
                    $days_from = min($all_days_array);
                    $days_to = max($all_days_array);
                }

            }

            return view('workers.show',compact('workerd','array_with_billings_dates', 'all_hours', 'days_from', 'days_to', 'today', 'hoursbill', 'lastBill','user'));

    }


    public function getPaid($workerID, Request $request){

        $workerd = Workers::find($workerID);
        $workers = DB::table('hours')
        ->where('hours.workers_id', '=', $workerID)
        ->select( 'hours.hours', 'hours.work_day', 'hours.id', 'hours.status_of_billings_worker')
        ->get();
        $days_from = 0;
        $days_to = 0;
    if(count($workers) > 0)
        {

            if(!empty($all_days_array) and !empty($all_hours_array))
            {
                $all_hours = array_sum($all_hours_array);
                $days_from = min($all_days_array);
                $days_to = max($all_days_array);
            }

        }

        $last_array =[$request->date];

        $last = DB::table('hours')
        ->where('workers_id', '=', $workerID)
        ->where('work_day', '<=', date($request->date))
        ->get();

        foreach($last as $l)
        {
            array_push($last_array, $l->work_day);
        }


        $array_dates = array();
        $array_hours = array();
        $hours = DB::table('hours')
        ->where('workers_id', '=', $workerID)
        ->where('status_of_billings_worker', '=', 0)
        ->where('hours.work_day', '>=', min($last_array))
        ->where('hours.work_day', '<=',$request->date)
        ->select('hours.status_of_billings_worker', 'hours.work_day', 'hours.hours')
        ->get();
        foreach($hours as $hour)
        {
            array_push($array_dates, $hour->work_day);
            array_push($array_hours, $hour->hours);

        }

        $deposite_array = [];

        $deposite = DB::table('billings')
        ->where('workers_id', '=', $workerID)
        ->where('billings.status_of_billings', '=', 0)
        ->where('billings.date', '>=', min($last_array))
        ->where('billings.date', '<=',$request->date)
        ->where('billings.category_id', '=', 4)
        ->select('billings.price', 'billings.status_of_billings', 'billings.date')
        ->get();

        foreach($deposite as $da)
        {
            array_push($deposite_array, $da->price);

        }
        $total_salary = array_sum($array_hours) * $workerd->price_hour;
        DB::table('billings')
        ->where('billings.workers_id', '=', $workerID)
        ->update(['status_of_billings' => 1]);

            $insert = DB::table('hoursbill')
            ->insert([
                [
                'hours'=>array_sum($array_hours),
                'workers_id' =>$workerID,
                'date_from' => min($array_dates),
                'date_to' =>$request->date,
                'deposit' => array_sum($deposite_array),
                'salary' => $total_salary


            ],]);


            DB::table('hours')
            ->where('hours.workers_id', '=', $workerID)
            ->whereBetween('work_day', [$days_from, $request->date] )
            ->select('hours.status_of_billings_worker')
            ->update(['status_of_billings_worker' => 1]);
            return back()->with('status','Rozliczono godziny pracownika');

    }

    public function edit(int $workerID)
    {
        $user = User::find(Auth::id());

        if ($user->hasPermissionTo('Edytuj pracownika')) {

        } else {
            return redirect()->route('home');
        }
        $worker = Workers::find($workerID);

        return view('workers.edit', compact('worker', 'workerID'));
    }

    public function update(int $workerID, Request $request, Workers $workers)
    {
        $worker = Workers::find($workerID);

        $worker->name = $request->input('name');
        $worker->surname = $request->input('surname');
        $worker->price_hour = $request->input('price_hour');
        $worker->address = $request->input('address');
        $worker->notes = $request->input('notes');
        $worker->status = $request->input('status');

        $worker->save();


        $user = User::find(Auth::id());

            DB::table('logs')->insert([
                [
                'name'=>'Aktualizacja pracownika',
                'worker_id' =>$worker->id,
                'created_at' =>date('d-m-Y H:i'),
                'user_id' =>  $user->id,


            ],

            ]);

        return redirect('workers/'.$worker->id)->with('status', 'Pracownik został zaktualizowany');

    }
    public function destroy(int $workerID, Workers $workers)
    {
        $worker = Workers::find($workerID);
        $workers->destroy($workerID);

        if($workers->getChanges())
            {
            return  back()->with('status', 'Pracownik nie został usunięty');
            }

            else
            {
                $user = User::find(Auth::id());
                    DB::table('logs')->insert([[
                        'name'=>'Usunięcie pracownika',
                        'created_at' =>date('d-m-Y H:i'),
                        'user_id' =>  $user->id,
                        'notes' => $worker->name." ".$worker->surname. " (". $worker->id.")",
                    ],
                    ]);
            return  redirect()->route('workers.index')->with('status', 'Pracownik został usunięty');
            }

            }



    public function deactivateWorker($id)
    {

        $worker = Workers::find($id);

        $worker->status = 0;
        $worker->save();
        if($worker->getChanges()){
            $isTrue = $worker->save();


            $user = User::find(Auth::id());

            if($isTrue){
                DB::table('logs')->insert([
                    [
                    'name'=>'Deaktywacja pracownika',
                    'worker_id' =>$worker->id,
                    'created_at' =>date('d-m-Y H:i'),
                    'user_id' =>  $user->id,


                ],

                ]);
            }
            return back()->with('status', 'Pracownik dezaktywowany');

        }else{
            return back()->with('status', 'Pracownik nie został dezaktywowany');

        }

    }
    public function activateWorker($id)
    {
        $worker = Workers::find($id);

        $worker->status = 1;
        $isTrue = $worker->save();
        $user = User::find(Auth::id());

        if($worker->getChanges()){
            if($isTrue){

                DB::table('logs')->insert([
                    [
                    'name'=>'Aktywacja pracownika',
                    'worker_id' =>$worker->id,
                    'created_at' =>date('d-m-Y H:i'),
                    'user_id' =>  $user->id,


                ],

                ]);
            }
            return back()->with('status', 'Pracownik aktywowany');

        }else{
            return back()->with('status', 'Pracownik nie został aktywowany');

        }
    }

    public function ajaxGet(int $workerID)
    {
        $workers = DB::table('workers')
        ->join('hours', 'workers.id' ,'=', 'hours.workers_id')
        ->join('contrahents', 'contrahents.id', '=', 'hours.contrahents_id')
        ->where('hours.workers_id', '=', $workerID)
        ->select('contrahents.name', 'workers.surname', 'hours.hours', 'hours.work_day', 'hours.id', 'hours.status_of_billings_worker')
        ->get();
        return response ()->json( $workers );
    }



    public function ajaxGetPaidHours(int $id)

    {


$bill = DB::table('hoursbill')
->where('workers_id', '=', $id)
->select('hoursbill.date_from', 'hoursbill.date_to', 'hours', 'salary', 'deposit', 'workers_id', 'id')
->get();
        return response ()->json(
             [ [
                 'bill'=>$bill


           ]]
            );
    }
    public function ajaxGetHours()

    {
        $work = Hours::all();
        $get_array = $work->workers->hours;
        return response()->json($get_array);
    }



}