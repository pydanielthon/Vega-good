<?php

namespace App\Http\Controllers;
use App\Models\Logs;
use App\Models\Users;
use App\Models\Workers;
use App\Models\Hours;
use App\Models\Billings;

use App\Models\Contrahents;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LogsController extends Controller
{
    public function index()
    {

        // $logs =Logs::with('workers','contrahents','hours','billings','users')->get();
        // $logs = Logs::getRelated()->getTable();
        // $logs = Logs::getRelations();
        $logs = DB::table('logs')
        ->leftJoin('workers', 'logs.worker_id' ,'=', 'workers.id')
        ->leftJoin('contrahents','logs.contrahent_id','=','contrahents.id')
        ->leftJoin('hours', 'logs.hour_id' ,'=', 'hours.id')
        ->leftJoin('billings', 'logs.billing_id' ,'=', 'billings.id')
        ->leftJoin('users', 'logs.user_id' ,'=', 'users.id')
        ->where('logs.id','>',0)
        ->select('logs.*', 'logs.created_at as logDate','workers.name as workerName','workers.surname as workerSurname','contrahents.name as contrahentName','users.name as userName', 'logs.name as logName', 'users.email as userEmail')
        ->orderBy('id', 'desc')->paginate(1500);
        // ->get();
        // $items = collect($logs)->groupBy('project_id');
        // $items->values()->all();
        // // dd($items);
// dd($logs);
        // foreach($logs as $log){
        //     $log;
        // }
       return view('logs.show', [
        'logs' => $logs
    ]);


    }

}