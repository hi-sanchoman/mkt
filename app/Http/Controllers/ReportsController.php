<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Report;
use App\Models\User;
use App\Models\Deal;
use App\Models\Organization;
use App\Models\Task;
use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;

class ReportsController extends Controller
{
    public function index()
    {   
        $deals = Deal::all();
        $mydeals = [];
        $statuses = [
           
            0 => 'Новый заказ',
                
            1 => 'Согласование с производством',
                
            2 => 'Расчет логистики / ТС',
                
            3 => 'Расчет сервиса',
            
            4 => 'Внутреннее согласование КП',
                
            
            5 => 'Согласование КП с заказчиком',
                
            
            6 => 'Составление договора с закачиком',
              
            
            7 => 'Согласование договора с заказчиком',
     
            
            8 => 'Заключение договора с заказчиком',
           
            
            9 => 'Выставление счета  заказчику',
       
            10 => 'Принято в работу',
                
            
            11 => 'Исполнение заявки на производство',
                
            
            12 => 'Отпуск готовой продукции  (внутреннее перемещение)',
                
            
            13 => 'Подготовка полного пакета документов для сдачи продукции заказчику',
                
            
            14 => 'Логистика (при необходимости)',
                
            
            15 => 'Поставка и ввод в эксплуатацию',
                
            
            16 => 'Исполнение договора',
                
            
            17 => 'Завершен',  
        
        ];

            $stagecounter = [];

        foreach ($deals as $key => $deal) {
            # code...
            $mydeals[] = [
                'name' => $deal->name,
                'progress' => $deal->status,
                'sum' => $deal->sum,
                'stage' => $statuses[$deal->status],
                'id' => $deal->id,
                'status' => $deal->status,
            ];

        }
        $deal = new Deal();
        //$task = 0;
        $dealscount = count(Deal::all());
        
        foreach($deal->STEPS as $key => $step) {
            $stagecounter[] = count(Deal::where('status',$key)->get())/$dealscount;
            //$$taskcompleted = 0;
            //$task = 0;
        }
    
        $counter = [];
        //$statuses = $deals->STATUSES;
        for($i = 0;$i < 18;$i++){
            $counter[] = count(Deal::where('status',$i)->get());
        }

        $mymanagers = User::where('position_id','11')->get();
        $managers = [];
        foreach ($mymanagers as $key => $manager) {
            # code...
            $managers[] = [
                'name' => $manager->first_name.' '.$manager->last_name,
                'clients' => Organization::where('responsible_id',$manager->id)->count(),
                'deals' => Deal::where('responsible_id',$manager->id)->count(),
                'sum' => Deal::where('responsible_id',$manager->id)->where('status','17')->sum('sum'),
                'fsum' => Deal::where('responsible_id',$manager->id)->sum('sum'),
            ];
        }
        

        return Inertia::render('Reports/Index',[
        	'reports' => Report::with('user')->get(),
        	'deals' => Deal::whereMonth('created_at', date('m'))->get()->count(),
        	'sum' => Deal::whereMonth('created_at', date('m'))->get()->sum('sum'),
            'managers' => $managers,
        	'users' => User::select(DB::raw("CONCAT(last_name, ' ', first_name)  AS label"), 'id as code')->where('account_id', 1)->get(),
        	'organizations' => Organization::all()->count(),
            'stage' => $counter,
            'sdelki' => $mydeals,
            'stagecounter' => $stagecounter,
        ]);
    }

    public function getDataByrange(Request $date){
       /*
        $deals = Deal::all();
        $mydeals = [];
        $statuses = [
           
            0 => 'Новый заказ',
                
            1 => 'Согласование с производством',
                
            2 => 'Расчет логистики / ТС',
                
            3 => 'Расчет сервиса',
            
            4 => 'Внутреннее согласование КП',
                
            
            5 => 'Согласование КП с заказчиком',
                
            
            6 => 'Составление договора с закачиком',
              
            
            7 => 'Согласование договора с заказчиком',
     
            
            8 => 'Заключение договора с заказчиком',
           
            
            9 => 'Выставление счета  заказчику',
       
            10 => 'Принято в работу',
                
            
            11 => 'Исполнение заявки на производство',
                
            
            12 => 'Отпуск готовой продукции  (внутреннее перемещение)',
                
            
            13 => 'Подготовка полного пакета документов для сдачи продукции заказчику',
                
            
            14 => 'Логистика (при необходимости)',
                
            
            15 => 'Поставка и ввод в эксплуатацию',
                
            
            16 => 'Исполнение договора',
                
            
            17 => 'Завершен',  
        
        ];*/
        /*foreach ($deals as $key => $deal) {
            # code...
            $mydeals[] = [
                'name' => $deal->name,
                'progress' => $deal->status,
                'sum' => $deal->sum,
                'stage' => $statuses[$deal->status],
                'id' => $deal->id,
            ];
        }
        $counter = [];
        //$statuses = $deals->STATUSES;
        for($i = 0;$i < 18;$i++){
            $counter[] = count(Deal::where('status',$i)->get());
        }*/

        if($date->date == "0"){
              $report = [
                'deals' => Deal::whereDay('created_at', date('d'))->get()->count(),
                'sum' => Deal::whereDay('created_at', date('d'))->get()->sum('sum'),
                'total' => Deal::whereDay('created_at', date('d'))->where('status','17')->get()->sum('sum'),
                'finisheddeals' => Deal::whereDay('created_at', date('d'))->where('status','17')->get()->count(),
            ];
           
            return $report;
            /*return Inertia::render('Reports/Index',[
            'reports' => Report::with('user')->get(),
            'deals' => Deal::whereDay('created_at', date('d'))->get()->count(),
            'sum' => Deal::whereDay('created_at', date('d'))->get()->sum('sum'),
            'users' => User::select(DB::raw("CONCAT(last_name, ' ', first_name)  AS label"), 'id as code')->where('account_id', 1)->get(),
            'organizations' => Organization::all()->count(),
            'stage' => $counter,
            'sdelki' => $mydeals,
        ]);*/
        }
        else if($date->date == "2"){
              $report = [
                'deals' => Deal::whereMonth('created_at', date('m'))->get()->count(),
                'sum' => Deal::whereMonth('created_at', date('m'))->get()->sum('sum'),
                'total' => Deal::whereMonth('created_at', date('m'))->where('status','17')->get()->sum('sum'),
                'finisheddeals' => Deal::whereMonth('created_at', date('m'))->where('status','17')->get()->count(),
            ];
           
            return $report;
           /* return Inertia::render('Reports/Index',[
            'reports' => Report::with('user')->get(),
            'deals' => Deal::whereMonth('created_at', date('m'))->get()->count(),
            'sum' => Deal::whereMonth('created_at', date('m'))->get()->sum('sum'),
            'users' => User::select(DB::raw("CONCAT(last_name, ' ', first_name)  AS label"), 'id as code')->where('account_id', 1)->get(),
            'organizations' => Organization::all()->count(),
            'stage' => $counter,
            'sdelki' => $mydeals,
            ]);*/
        }
        else if($date->date == "3"){
            $report = [
                'deals' => Deal::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->get()->count(),
                'sum' => Deal::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->get()->sum('sum'),
                'total' => Deal::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->where('status','17')->get()->sum('sum'),
                'finisheddeals' => Deal::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->where('status','17')->get()->count(),
            ];
           

            return $report;
           /* Carbon::setWeekStartsAt(Carbon::MONDAY);
            Carbon::setWeekEndsAt(Carbon::SUNDAY);
            return Inertia::render('Reports/Index',[
                'reports' => Report::with('user')->get(),
                'deals' => Deal::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->get()->count(),
                'sum' => Deal::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->get()->sum('sum'),
                'users' => User::select(DB::raw("CONCAT(last_name, ' ', first_name)  AS label"), 'id as code')->where('account_id', 1)->get(),
                'organizations' => Organization::all()->count(),
                'stage' => $counter,
                'sdelki' => $mydeals,
            ]);*/
        }
        else{
           
            $report = [
                'deals' => Deal::whereYear('created_at', date('Y'))->get()->count(),
                'sum' => Deal::whereYear('created_at', date('Y'))->get()->sum('sum'),
                'total' => Deal::whereYear('created_at', date('Y'))->where('status','17')->get()->sum('sum'),
                'finisheddeals' => Deal::whereYear('created_at', date('Y'))->where('status','17')->get()->count(),
            ];
            return $report;
           /* return Inertia::render('Reports/Index',[
                'reports' => Report::with('user')->get(),
                'deals' => Deal::whereYear('created_at', date('y'))->get()->count(),
                'sum' => Deal::whereYear('created_at', date('y'))->get()->sum('sum'),
                'users' => User::select(DB::raw("CONCAT(last_name, ' ', first_name)  AS label"), 'id as code')->where('account_id', 1)->get(),
                'organizations' => Organization::all()->count(),
                'stage' => $counter,
                'sdelki' => $mydeals,
            ]);*/
        }
        
    }

    public function analytics()
    {
    	return Inertia::render('Reports/Analytics',[
    		'users' => User::all(),
    	]);
    }

    public function create()
    {
    	return Inertia::render('Reports/Create');
    }
}
