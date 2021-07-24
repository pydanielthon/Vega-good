<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Hours;
use App\Models\Workers;
use App\Models\Contrahents;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class HoursController extends Controller
{



    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = User::find(Auth::id());

        if ($user->hasPermissionTo('Dodaj godziny')) {

        } else {
            return redirect()->route('home');
        }
        // $user = User::find(Auth::id());
        // $user->assignRole('Super-admin');
        $hours = Hours::all()->sortBy('id');
        $workers = Workers::all();
        $contrahents = Contrahents::all();

        return view('hours.create',  [
            'workers' => $workers,
            'contrahents' => $contrahents


        ]);

        return view('hours.create',  [
            'workers' => $workers,
            'contrahents' => $contrahents


        ]);
    }


    public function create()
    {

        $workers = Workers::all();
        $contrahents = Contrahents::all();
        $day = date('d');
        $month = date('m');
        $year = date('Y');
        $today = $year . '-' . $month . '-' . $day;
        return view(
            'hours.create',
            [
                'workers' => $workers,
                'contrahents' => $contrahents,
                'today' => $today


            ]
        );
    }


    public function store(Request $request)
    {
        //   dd(count($request->input('work_day')));


        for ($i = 0; $i < count($request->input('work_day')); $i++) {

            $hours = new Hours();
            $hours->contrahents_id = $request->input('contrahentID')[$i];
            $hours->workers_id = $request->input('workerID');
            $hours->work_day = $request->input('work_day')[$i];
            $hours->hours = $request->input('hours')[$i];
            $hours->workers_price_hour = Workers::find($hours->workers_id)->price_hour;
            $hours->contrahents_salary_cash = Contrahents::find($hours->contrahents_id)->salary_cash;
            $hours->contrahents_salary_invoice = Contrahents::find($hours->contrahents_id)->salary_invoice;

            $contrahent = Contrahents::find($hours->contrahents_id);
            $worker = Workers::find($hours->workers_id);

            $worker->status = 1;
            $worker->statusclick = 1;

            $contrahent->status = 1;
            $contrahent->statusclick = 1;

            $worker->save();
            $contrahent->save();

            $hours->status_of_billings_contrahent = false;
            $hours->status_of_billings_worker = false;

          $isHours = $hours->save();
          $user = User::find(Auth::id());
            if($isHours){
                DB::table('logs')->insert([
                    [
                    'name'=>'Dodanie godzin',
                    'worker_id' =>$request->input('workerID'),
                    'contrahent_id'=>$request->input('contrahentID')[$i],
                    'created_at' =>date('d-m-Y H:i'),
                    'user_id' =>  $user->id,
                    'notes' => 'Dodano: '. $request->input('hours')[$i]. " godzin"


                ],

                ]);
            }
        }



if($isHours){

    return back()
    ->with('status', 'Godziny zostały dodane');

}else{
    return back()
    ->with('status', 'Godziny nie zostały dodane');

}
    }


    public function show(Hours $hours)
    {
        return view('hours.show', compact('hours'));
    }


    public function edit(int $hoursID)
    {
        $hour = Hours::find($hoursID);
        $worker = Workers::find($hour->workers_id);
        $contrahent = Contrahents::find($hour->contrahents_id);
        $allContrahents=Contrahents::All();
		return view('hours.edit', compact('hour', 'hoursID', 'worker', 'contrahent','allContrahents'));

    }


    public function update(int $hoursID, Request $request, Hours $hours)
    {
        $hour = Hours::find($hoursID);
        // dd($hour->workers_id);
        $hour->contrahents_id = $request->input('inputContrahentName');
        $hour->hours = $request->input('inputHours');
        $hour->work_day = $request->input('inputDay');

        // dd($hour->hours);
        $isHours = $hour->save();
        $user = User::find(Auth::id());


        if($isHours){
            DB::table('logs')->insert([
                [
                'name'=>'Aktualizacja godziny',
                'worker_id' =>$hour->workers_id,
                'contrahent_id'=>$hour->contrahents_id,
                'created_at' =>date('d-m-Y H:i'),
                'user_id' =>  $user->id,


            ],

            ]);
        }



        return redirect()->route('workers.show', $hour->workers_id)->with('status', 'Godziny zaktualizowane');

    }

    public function destroy(int $hoursID, Hours $hours)
    {
        $hour = Hours::find($hoursID);

        $hours->destroy($hoursID);
        $isHours = $hour->save();


        $user = User::find(Auth::id());

        if($isHours){
            DB::table('logs')->insert([
                [
                'name'=>'Usunięcie godziny',
                'worker_id' =>$hour->workers_id,
                'contrahent_id'=>$hour->contrahents_id,
                'created_at' =>date('d-m-Y H:i'),
                'user_id' =>  $user->id,


            ],

            ]);
        }
        return back()->with('status','Godziny usunięte pomyślnie');

    }

public function list( Hours $hours){
    $hour = Hours::all();

    return view('hours.list_all', compact('hour'));
}


public function ajaxGetHours(Request $request){
    $from = empty($request->dateFrom) ? '1970-01-01' : $request->dateFrom;
    $to = empty($request->dateTo) ? date('Y-m-d') : $request->dateTo;
    // $queryData = DB::table('billings')->where('date', $request->dateFrom);
    if($from>$to)
    {
        return response()->json(['error'=>'Data od nie moze byc wieksza od daty do']);
    }
    $workers = DB::table('workers')
        ->get();

        $data = array();

        for($i = 0; $i < count($workers); $i++)
        {
            $hours = DB::table('workers')
            ->leftJoin('hours', 'hours.workers_id' ,'=', 'workers.id')
            ->where('hours.workers_id', '=', $workers[$i]->id)
            ->get();

            $hours2 = DB::table('hours')
            ->where('hours.workers_id', '=', $workers[$i]->id)
            ->where('hours.work_day', '<=', $to)
            ->where('hours.work_day', '>=', $from)
            ->sum('hours');

            $data[$i]['name'] = $workers[$i]->name;
            $data[$i]['surname'] = $workers[$i]->surname;
            $data[$i]['id'] = $workers[$i]->id;

            $data[$i]['total_hours'] = $hours2;
            $data[$i]['edit_link'] =substr($request->show, 0, -1).'workers-generate/';

        }





    return response()->json($data);

}

public function ajaxGetHoursContr(Request $request){
    $from = empty($request->dateFrom) ? '1970-01-01' : $request->dateFrom;
    $to = empty($request->dateTo) ? date('Y-m-d') : $request->dateTo;
    // $queryData = DB::table('billings')->where('date', $request->dateFrom);
    if($from>$to)
    {
        return response()->json(['error'=>'Data od nie moze byc wieksza od daty do']);
    }
    $contrahents = DB::table('contrahents')
        ->get();

        $data = array();

        for($i = 0; $i < count($contrahents); $i++)
        {

            $hours = DB::table('contrahents')
            ->leftJoin('hours', 'hours.contrahents_id' ,'=', 'contrahents.id')
            ->where('hours.contrahents_id', '=', $contrahents[$i]->id)
            ->get();

            $hours2 = DB::table('hours')
            ->where('hours.contrahents_id', '=', $contrahents[$i]->id)
            ->where('hours.work_day', '<=', $to)
            ->where('hours.work_day', '>=', $from)
            ->sum('hours');

            $data[$i]['name'] = $contrahents[$i]->name;
            $data[$i]['id'] = $contrahents[$i]->id;

            $data[$i]['total_hours'] = $hours2;
            $data[$i]['edit_link'] =substr($request->show, 0, -1).'contrahents-generate/';
        }
    return response()->json($data);
}

}
