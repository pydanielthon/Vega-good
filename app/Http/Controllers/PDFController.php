<?php

namespace App\Http\Controllers;
use App\Models\Workers;
use App\Models\Hours;
use App\Models\Billings;
use App\Models\Category;
use App\Models\Contrahents;
use App\Models\Hoursbill;
use Illuminate\Http\Request;
use PDF;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Input\Input;

class PDFController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */



    public function generateContrahentPaidHoursPdf(int $id){
        $last_array =['1970-01-01'];

        $hoursbill = Hoursbill::select('date_from', 'date_to', 'contrahents_id', 'hours')->where('hoursbill.id', '=', $id)->get();

        $date_from = $hoursbill[0]['date_from'];
        $date_from = date('Y-m-d', strtotime($date_from));
        $date_to = $hoursbill[0]['date_to'];
        $total_hours = $hoursbill[0]['hours'];
        $contrahentId = $hoursbill[0]['contrahents_id'];
        $deposit = 0;
        $price_hour = 0;
        $last = DB::table('hoursbill')
        ->where('contrahents_id', '=', $contrahentId)
        ->select('hoursbill.date_to')
        ->get();


        foreach($last as $l)
        {
            array_push($last_array, $l->date_to);
        }

        $arr_last = array_values(array_slice($last_array, -1))[0];
        $arr_last2 = Carbon::createFromFormat('Y-m-d', $arr_last)->addDays(1)->format('Y-m-d');

        $workers = DB::table('contrahents')
        ->where('contrahents.id', '=', $id)
        ->get();

        foreach($workers as $worker)
        {
            $price_hour = $worker->salary_invoice;
        }
        $hours = DB::table('hours')
        ->join('workers', 'workers.id','=', 'hours.workers_id')
        ->where('hours.work_day', ">=", $date_from)
        ->where('hours.work_day', "<=", $date_to)

        ->where('hours.status_of_billings_contrahent', '=', 1)
        ->where('hours.contrahents_id', '=', $contrahentId)
        ->get();
        $total_salary = $price_hour * $total_hours;
        $pdf = PDF::loadView('pdf.contrahentsPDF', [
            'hoursbill' => $hoursbill,
            'date_from' => $date_from,
            'date_to' => $date_to,
            'total_hours' => $total_hours,
            'hours' => $hours,
            'deposit' =>$deposit,
            'price_hour'=>$price_hour,
            'total_salary' =>  $total_salary,

        ]);

       return $pdf->download('GodzinyKontrahenta.pdf');
    }

    public function generatePdf($id)
    {
        $hoursbill = Hoursbill::select('date_from', 'date_to', 'workers_id', 'hours')
        ->where('hoursbill.id', '=', $id)
        ->get();

        $date_from = $hoursbill[0]['date_from'];
        $date_to = $hoursbill[0]['date_to'];
        $total_hours = $hoursbill[0]['hours'];
        $workerID = $hoursbill[0]['workers_id'];
        $deposit = 0;
        $price_hour = 0;


        $workers = DB::table('workers')
        ->where('workers.id', '=', $workerID)
        ->get();
        foreach($workers as $worker)
        {
            $price_hour = $worker->price_hour;
        }
        $hours = DB::table('hours')
        ->where('hours.workers_id', '=', $workerID)
        ->where('hours.work_day', ">=", $date_from)
        ->where('hours.work_day', "<=", $date_to)
        ->where('hours.status_of_billings_worker', '=', 1)
        ->min('hours.work_day');

       $date_from = $hours;

        $billings = DB::table('billings')
        ->where('workers_id', '=', $workerID)
        ->where('billings.date', '>=', $date_from)
        ->where('billings.date', '<=', $date_to)
        ->where('status_of_billings', '=', 1)
        ->get();

        $depo = 0;
        foreach($billings as $bill)
        {
            $deposit += $bill->price;
        }


        $to = \Carbon\Carbon::createFromFormat('Y-m-d', $date_from);
        $from = \Carbon\Carbon::createFromFormat('Y-m-d', $date_to);
        $diff_in_days = $to->diffInDays($from);
        $electricity = $diff_in_days * $workers[0]->electric_price;
        $total_salary = $price_hour * $total_hours;
        $total_salary_minus_deposit = $total_salary - $deposit - $electricity;

        $pdf = PDF::loadView('pdf.workersPDF', [
            'hoursbill' => $hoursbill,
            'date_from' => $date_from,
            'date_to' => $date_to,
            'total_hours' => $total_hours,
            'billings' => $billings,
            'depo' =>$depo,
            'deposit' =>$deposit,
            'price_hour'=>$price_hour,
            'total_salary' =>  $total_salary,
            'total_salary_minus_deposit' =>$total_salary_minus_deposit,
            'electricity' => $electricity

        ]);

        return $pdf->download('GodzinyPracownika.pdf');
    }

    public function generateWorkerPaidHoursPdf($id )
    {

        $all_hours_array=[];
        $all_days_array = [];
        $all_deposit_array = [];
        $all_additional_costs_array = [];
        $all_dep_num = 0;
        $all_ac_num = 0;
        $all_hours = 0;
        $hours_unpaid_from  = "";
        $hours_unpaid_to  = "";
        $status = Workers::find($id);

        $workers = DB::table('hours')
            ->where('hours.workers_id', '=', $id)
            ->select( 'hours.hours', 'hours.work_day', 'hours.id', 'hours.status_of_billings_contrahent')
            ->get();

        $billings = DB::table('billings')
            ->join('category', 'category.id', '=', 'billings.category_id')
            ->where('billings.workers_id', '=', $id)
            ->select('billings.id', 'billings.price' ,'billings.date', 'billings.notes', 'category.name', 'category.id')
            ->get();

        if(count($billings) > 0 )
        {
            foreach($billings as $bill)
            {
                if($bill->id == 4)
                {
                    array_push($all_deposit_array, $bill->price);

                }
                if($bill->id == 12)
                {
                    array_push($all_additional_costs_array, $bill->price);

                }

            }
        }
$all_dep_num = array_sum($all_deposit_array);
$all_ac_num = array_sum($all_additional_costs_array);

        if(count($workers) > 0)
            {
                foreach($workers as $worker)
                {
                    if($worker->status_of_billings_contrahent == 0)
                        {
                            array_push($all_hours_array, $worker->hours);
                            array_push($all_days_array, $worker->work_day);

                        }

                }
                if(!empty($all_days_array) and !empty($all_hours_array))
                {
                    $all_hours = array_sum($all_hours_array);
                    $hours_unpaid_from = min($all_days_array);
                    $hours_unpaid_to = max($all_days_array);
                }

            }

                $unpaid_hours = array_sum($all_hours_array);
                $to = \Carbon\Carbon::createFromFormat('Y-m-d', $hours_unpaid_from);
                $from = \Carbon\Carbon::createFromFormat('Y-m-d', $hours_unpaid_to);
                $diff_in_days = $to->diffInDays($from);
                $days_multiply = $diff_in_days ;
                $total_salary = $status->price_hour * $unpaid_hours;
                $total_to_pay = $total_salary - $all_dep_num - $all_ac_num;



        $pdf = PDF::loadView('pdf.workersPDF', [
             'workers'=>$workers,
             'status' =>$status,
             'hours_unpaid_from' => $hours_unpaid_from,
             'hours_unpaid_to' => $hours_unpaid_to,
             'diff_in_days' => $diff_in_days,
             'days_multiply' => $days_multiply,
             'unpaid_hours'=> $unpaid_hours,
             'total_salary'=>$total_salary,
             'billings' =>$billings,
             'all_ac_num' => $all_ac_num,
             'all_dep_num' => $all_dep_num,
             'total_to_pay' => $total_to_pay,

        ]);

        return $pdf->download('GodzinyPracownika.pdf');
    }

    public function generateContrahentPaidHoursDataPdf($id, Request $request)
    {
        $from =  $request->input('frompdf');
        $to =  $request->topdf;

    }

    public function generateBillingsPdf(Request $request)
    {


        // dd($request->all());
        $from = empty($request->frompdf) ? '1970-01-01' : $request->frompdf;
        $to = empty($request->topdf) ? date('Y-m-d') : $request->topdf;
        // $queryData = DB::table('billings')->where('date', $request->dateFrom);
        $sum = 0;
        $catNameGeneral = 'Sumarycznie';
        if ($request->categorypdf > 0 and !($request->categorypdf == 's')) {
            $getCat = Category::select('name')->where('id', '=', $request->categorypdf)->get();
            $catNameGeneral = $getCat[0]['name'];
        } elseif ($request->categorypdf == 's') {
            $catNameGeneral = 'zaliczki podsumowanie';
        }
        if ($catNameGeneral == 'zaliczki podsumowanie') {

            $queryData = Billings::where('date', '>=', $from)
                ->where('date', '<=', $to)
                ->whereIn('category_id', [4, 10])
                ->orderBy('category_id')
                ->get();


            for ($i = 0; $i < count($queryData); $i++) {

                $catName = Category::select('name')
                    ->where('id', '=', $queryData[$i]['category_id'])->get();

                $queryData[$i]['category_name'] = $catName[0]['name'];

                $sum += $queryData[$i]['price'];

                if ($queryData[$i]['workers_id']) {
                    $workerData = Workers::select('name', 'surname')->where('id', '=', $queryData[$i]['workers_id'])->get();
                    $queryData[$i]['workers_name'] = $workerData[0]['name'] . ' ' . $workerData[0]['surname'];
                }

                if ($queryData[$i]['contrahents_id']) {
                    $workerData = Contrahents::select('name')->where('id', '=', $queryData[$i]['contrahents_id'])->get();
                    $queryData[$i]['contrahents_name'] = $workerData[0]['name'];
                }


                $queryData[$i]['date'] = date('d-m-Y', strtotime($queryData[$i]['date']));
            }

            $pdf = PDF::loadView('pdf.billingsPDF', ['requested' => $queryData, 'sum' => $sum, 'to' => $to, 'from' => $from, 'notes' => $request->notespdf, 'category' => $catNameGeneral]);
        } else {
            if (!empty($request->notespdf)) {

                $queryData = Billings::where('date', '>=', $from)
                    ->where('date', '<=', $to)
                    ->where('category_id', $request->categorypdf == 0 ? '>' : '=', $request->categorypdf)
                    ->where('notes', 'like', '%' . $request->notespdf . '%')
                    ->get();
            } else {
                $queryData = Billings::where('date', '>=', $from)
                    ->where('date', '<=', $to)
                    ->where('category_id', $request->categorypdf == 0 ? '>' : '=', $request->categorypdf)
                    ->get();
            }

            for ($i = 0; $i < count($queryData); $i++) {

                $catName = Category::select('name')
                    ->where('id', '=', $queryData[$i]['category_id'])->get();

                $queryData[$i]['category_name'] = $catName[0]['name'];

                $sum += $queryData[$i]['price'];

                $queryData[$i]['date'] = date('d-m-Y', strtotime($queryData[$i]['date']));
            }

            $pdf = PDF::loadView('pdf.billingsPDF', ['requested' => $queryData, 'sum' => $sum, 'to' => $to, 'from' => $from, 'notes' => $request->notespdf, 'category' => $catNameGeneral]);
        }

        return $pdf->download('Rozliczenia.pdf');
    }

    public function GetHoursWorkPdf(int $id, string $from,  string $to){

        $from = empty($from) ? '1970-01-01' : $from;
        $to = empty($to) ? date('Y-m-d') : $to;
        $worker = Workers::find($id);

        $hours = Hours::where('hours.work_day', '<=', $to)
        ->where('hours.work_day', '>=', $from)
        ->where('hours.workers_id', '=', $id)
        ->orderBy('work_day', 'asc')
        ->get();


        for($i = 0;  $i < count($hours); $i++){
            $contrahent = Contrahents::select('name')->where('id', '=', $hours[$i]->contrahents_id)->get();
            $hours[$i]['contrahents_name'] = $contrahent[0]['name'];
            $hours[$i]['workers_name'] = $worker->name;
            $hours[$i]['workers_surname'] = $worker->surname;

        }

            $pdf = PDF::loadView('pdf.hoursWorkPdf', [
                'hours'=>$hours,
                'from' =>$from,
                'to' => $to,
           ]);

           return $pdf->download('GodzinyPracownika.pdf');
        }

    public function GetHoursContrPdf( int $id, string $from,  string $to){

        $from = empty($from) ? '1970-01-01' : $from;
        $to = empty($to) ? date('Y-m-d') : $to;
        $contrahents = DB::table('hours')
        ->join('contrahents', 'hours.contrahents_id', '=', $id)
        ->join('workers', 'hours.workers_id','=','workers.id')
        ->where('hours.work_day', '<=', $to)
        ->where('hours.work_day', '>=', $from)
        ->where('contrahents.id', '=', $id)
        ->orderBy('work_day', 'asc')
        ->get();

            $pdf = PDF::loadView('pdf.hoursContrPdf', [
                'contrahents'=>$contrahents,
                'from' =>$from,
                'to' => $to,
           ]);

           return $pdf->download('GodzinyKontrahenta.pdf');
        }


        public function generateBillingsPdfSummary(Request $request){
            $from = empty($request->frompdf) ? '1970-01-01' : $request->frompdf;
            $to = empty($request->topdf) ? date('Y-m-d') : $request->topdf;
            $workers = DB::table('hours')
            ->join('contrahents', 'hours.contrahents_id', '=', 'contrahents.id')
            ->join('workers', 'hours.workers_id','=','workers.id')
            ->where('hours.work_day', '<=', $to)
            ->where('hours.work_day', '>=', $from)
            ->select('workers.id','hours.workers_id','hours.workers_price_hour','hours.contrahents_id','hours.status_of_billings_contrahent','hours.status_of_billings_worker','contrahents.salary_cash','contrahents.salary_invoice', 'workers.name', 'workers.surname', 'contrahents.name as contrahent_name', 'hours.hours', 'hours.work_day', 'workers.price_hour', 'contrahents.id', 'contrahents_salary_cash', 'contrahents_salary_invoice')
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

                // $workerData = Contrahents::select('name')->where('id', '=', $workers[$i]['contrahents_id'])->get();
                // $data[$i]['contrahents_name'] = $workerData[0]['name'];
            }

            $pdf = PDF::loadView('pdf.billingsPdfSummary', [
                'workers'=>$workers ,
                'from' =>$from,
                'to' => $to,
           ]);

           return $pdf->download('billingsSummary.pdf');
        }
}
