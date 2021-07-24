<?php

namespace App\Http\Controllers;

use App\Models\Billings;
use App\Models\Hours;
use App\Models\Workers;
use App\Models\Contrahents;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Queue\Worker;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class BillingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = User::find(Auth::id());

        if ($user->hasPermissionTo('Dostep do bilansu')) {

        } else {
            return redirect()->route('home');
        }
        $billings = Billings::all()->sortBy('id');
        $category = Category::all();
        $user=  User::find(Auth::id())->hasPermissionTo('Edytuj/usun w bilansie');

        return view(
            'billings.list',
            [
                'billings' => $billings,
                'category' => $category,
                'user'=>$user,
            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = User::find(Auth::id());

        if ($user->hasPermissionTo('Dodaj do bilansu')) {

        } else {
            return redirect()->route('home');
        }
        $workers = Workers::all();
        $contrahents = Contrahents::all();
        $category = Category::all();
        return view(
            'billings.create',
            [
                'workers' => $workers,
                'contrahents' => $contrahents,
                'category' => $category


            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        // dd($request->input('workerID'));
        for ($i = 0; $i < count($request->input('date')); $i++) {
            $billings = new Billings();
            $billings->contrahents_id = $request->input('contrahentID')[$i];
            $billings->workers_id = $request->input('workerID')[$i];
            $billings->price = $request->input('price')[$i];
            $billings->date = $request->input('date')[$i];
            $billings->category_id = $request->input('categoryID')[$i];
            $billings->notes = $request->input('notes')[$i];

            $isAdd=$billings->save();
            $catName = Category::select('name')->where('id', '=', $request->input('categoryID')[$i])->get();

            if($isAdd){
                $user = User::find(Auth::id());
                DB::table('logs')->insert([
                    [
                    'name'=>'Dodanie rozliczenia',
                    'contrahent_id'=>$request->input('contrahentID')[$i],
                    'worker_id'=>$request->input('workerID')[$i],
                    'created_at' =>date('d-m-Y H:i'),
                    'user_id' =>  $user->id,
                    'notes' => "Dodano kwotę: ". $request->input('price')[$i]. " do kategorii " .$catName[0]->name
                    ],
                ]);

            }else{
                $user = User::find(Auth::id());
                DB::table('logs')->insert([
                    [
                    'name'=>'Próba dodania rozliczenia',
                    'contrahent_id'=>$request->input('contrahentID')[$i],
                    'worker_id'=>$request->input('workerID')[$i],
                    'created_at' =>date('d-m-Y H:i'),
                    'user_id' =>  $user->id,
                    ],
                ]);

            }
        }
        return back()->with('status', 'Dodano rozliczenie');


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Billings $billings)
    {
        return view('billings.show', compact('billings'));
    }

    public function ajaxGetBillings(Request $request)
    {

        $from = empty($request->dateFrom) ? '1970-01-01' : $request->dateFrom;
        $to = empty($request->dateTo) ? date('Y-m-d') : $request->dateTo;
        // $queryData = DB::table('billings')->where('date', $request->dateFrom);
        if($from>$to)
        {
            return response()->json(['error'=>'Data od nie moze byc wieksza od daty do']);
        }
        if($request->cat == 's'){
            $queryData = Billings::where('date', '>=', $from)
            ->where('date', '<=', $to)
            ->whereIn('category_id',[4,10])
            // ->orWhere('category_id','=','10')
            // ->orderBy('category_id','ASC')

            ->get();

        for ($i = 0; $i < count($queryData); $i++) {

            if ($queryData[$i]['workers_id']) {
                $workerData = Workers::select('name', 'surname')->where('id', '=', $queryData[$i]['workers_id'])->get();
                $queryData[$i]['workers_name'] = $workerData[0]['name'] . ' ' . $workerData[0]['surname'];
            }

            if ($queryData[$i]['contrahents_id']) {
                $workerData = Contrahents::select('name')->where('id', '=', $queryData[$i]['contrahents_id'])->get();
                $queryData[$i]['contrahents_name'] = $workerData[0]['name'];
            }

            // $queryData[$i]['edit_link'] =substr($request->show, 0, -1).$queryData[$i]['id'].'/edit';
            // $queryData[$i]['delete_link'] =substr($request->deleteUrl, 0, -1).$queryData[$i]['id'];
            // $queryData[$i]['show_link'] =substr($request->show, 0, -1).$queryData[$i]['id'];
          // ?
            $queryData[$i]['date']= date('d-m-Y',strtotime($queryData[$i]['date']));
        }
        }else{

            if(empty($request->inNotes)){
                $queryData = Billings::where('date', '>=', $from)
                ->where('date', '<=', $to)->where('category_id', $request->cat == 0 ? '>' : '=', $request->cat)
                ->get();
            }else{
                $queryData = Billings::where('date', '>=', $from)
                ->where('date', '<=', $to)->where('category_id', $request->cat == 0 ? '>' : '=', $request->cat)->where('notes', 'like', '%' . $request->inNotes . '%')
                ->get();
            }


        for ($i = 0; $i < count($queryData); $i++) {

            $catName = Category::select('name')->where('id', '=', $queryData[$i]['category_id'])->get();

            $queryData[$i]['category_name'] = $catName[0]['name'];
            if ($queryData[$i]['workers_id']) {
                $workerData = Workers::select('name', 'surname')->where('id', '=', $queryData[$i]['workers_id'])->get();
                $queryData[$i]['workers_name'] = $workerData[0]['name'] . ' ' . $workerData[0]['surname'];
            }

            if ($queryData[$i]['contrahents_id']) {
                $workerData = Contrahents::select('name')->where('id', '=', $queryData[$i]['contrahents_id'])->get();
                $queryData[$i]['contrahents_name'] = $workerData[0]['name'];
            }

            $queryData[$i]['edit_link'] =substr($request->show, 0, -1).$queryData[$i]['id'].'/edit';
            $queryData[$i]['delete_link'] =substr($request->deleteUrl, 0, -1).$queryData[$i]['id'];
            $queryData[$i]['show_link'] =substr($request->show, 0, -1).$queryData[$i]['id'];
          // ?
            $queryData[$i]['date']= date('d-m-Y',strtotime($queryData[$i]['date']));
        }
        }

        // $queryData = json_encode($queryData);
        return response()->json($queryData);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Billings $billing)
    {
        // dd($billing);
        // if($billing->workers_id){
        //     $workerName=Workers::select('name','surname')->where('id','=',$billing->workers_id);

        // }else
        // $contrahentName=Contrahents::select('name')->where('id','=',$billing->contrahents_id);
           $workers=Workers::all();
           $contrahents=Contrahents::all();
           $category = Category::all();
        return view('billings.edit', compact('billing','workers','contrahents','category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,int $id)
    {


        //  dd($request->workers_id);
         $billing=Billings::select('*')->where('id','=',$id);
      $test = $billing->update(['workers_id'=>$request->workers_id,'contrahents_id'=>$request->contrahents_id,'date'=>$request->date,'category_id'=>$request->category_id,'price'=>$request->price,'notes'=>$request->notes]);
if($test){
    return back()->with('status','Rozliczenie zostało zaktualizowane');

}else{
    return back()->with('status','Rozliczenie nie zostało zaktualizowane');

}
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function ajaxGetDestroyBilling(int $id)
    {
        Billings::where('id', $id)->delete();

        return back()->with('status','Usunięto rozlicznie');
    }


public function summaryView()
{


    return view('billings.summary');
}
public function summary(Request $request){
        $from = empty($request->dateFrom) ? '1970-01-01' : $request->dateFrom;
        $to = empty($request->dateTo) ? date('Y-m-d') : $request->dateTo;

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
        }




        return response()->json($workers);
    }
}
