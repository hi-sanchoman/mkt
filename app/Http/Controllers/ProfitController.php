<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Models\Income;
use App\Models\Expense;
use App\Models\Category;
use App\Models\User;
use App\Models\Owe;
use App\Models\Salary;
use App\Models\Worker;
use App\Models\Owerealizator;
use App\Models\Oweshop;
use App\Models\Oweother;
use App\Models\Supplier;
use App\Models\Magazine;
use App\Models\Totalreport;
use App\Models\Assortment;
use App\Models\Store;
use App\Models\Report;
use App\Models\Ostatok;
use App\Models\Pivot;
use App\Models\Nak;
use Carbon\Carbon;
use App\Models\Month;
/**
 * 
 */
class ProfitController extends Controller
{
		
	public function index(){
		$month = Month::orderBy('id','desc')->first();
		$income1 = Income::sum('sum')-Expense::sum('sum');
		$income = Income::orderBy('id','desc')->get();		
		$expense = Expense::where('kassa','!=', 9)->orderBy('id','desc')->get();
		$expenseMilk = Expense::where('kassa', '!=', 4)->where('category_id', 4)->orderBy('id','desc')->get();
		$category = Category::all();
		$users = User::orderBy('first_name', 'asc')->get();
		$owes = Magazine::dolgi();
		$owerealizator = Owerealizator::all();
		$oweshop = Oweshop::all();
		$oweother = Oweother::all();
		$zarplata = Salary::where('finished','0')->whereMonth('created_at', $month->month)->whereYear('created_at',$month->year)->with('worker')->get();
		// dd($zarplata->toArray());

		$workers = Worker::whereNotIn('id',Salary::select('worker_id')->where('finished','0')->groupBy('worker_id')->whereMonth('created_at', Carbon::now()->month)->with('worker')->pluck('worker_id'))->get();
        
		// for excel report
		$exportZarplata = [];
		foreach ($zarplata as $z) {
			$exportZarplata[] = [
				'worker.name' => $z->worker->name . " " . $z->worker->surname,
				"worker.salary" => $z->worker->salary,
				"nalog" => $z->OSMS + $z->IPN + $z->OPV,
 				"result_oklad" => $z->total_income,
 				"days" => $z->days,
 				"total_income" => $z->total_income,
 				"initial_saldo" => $z->initial_saldo,
 				"end_saldo" => $z->end_saldo,
 				"rospis" => '',
			];
		}
		// dd($exportZarplata);


		$days = [];
		for($i = 0; $i < Worker::count();$i++){
			$days[] = 0;
		}
		$dolgi = Magazine::dolgi();
		
		$left = Income::whereMonth('created_at', Carbon::now()->month)->sum('sum');

		$totalreports = Totalreport::all();

		$ostatok = Ostatok::whereMonth('created_at',Carbon::now()->month)->whereYear('created_at',Carbon::now()->year)->value('ostatok');

		// dd($expense->toArray());


		$expenses = [];


		foreach ($expense as $key => $exp) {
			if ($exp->category_id == 4) continue;

			$mY = $exp->created_at->format('n-Y');
			$y = $exp->created_at->format('Y');

			if (!isset($expenses[$mY])) {
				$expenses[$mY] = []; 
			}

			if (!isset($expenses[$mY][$exp->category_id])) {
				$expenses[$mY][$exp->category_id] = [
					'name' => $exp->category_id,
					'sum' => 0,
					'date' => $mY,					
				];
			}

			if (!isset($expenses[$mY]['total'])) {
				$expenses[$mY]['total'] = 0;
			}

			$expenses[$mY][$exp->category_id]['sum'] += $exp->sum;
			$expenses[$mY]['total'] += $exp->sum;
		}

		// dd($expense->toArray());

		return Inertia::render('Profit/Index',[
			'owes2' => Magazine::dolgi2(),
			'left' => $left,
			'incomes' => $income,
			'income' => $income1,
			'expenses' => $expense,
			'categories' => $category,
			'users' => $users,
			'owes1' => $owes,
			'zarplata' => $zarplata,
			'workers' => $workers,
			'days' => $days,
			'saldo' => $days,
			'owerealizator' => $owerealizator,
			'oweshop1' => $oweshop,
			'oweother' => $oweother,
			'dolgi' => $dolgi,
			'totalreports' => $totalreports,
			'total_expenses' => $expenses,
			'milk_expenses' => $expenseMilk,
			'ostatok1' => $ostatok == null ? 0 : $ostatok,
			'export_zarplata' => $exportZarplata,
		]);
	}

	public function zarplata(){

		$income1 = Income::sum('sum')-Expense::sum('sum');
		$income = Income::all();		
		$expense = Expense::all();
		$category = Category::all();
		$users = User::all();
		$owes = Owe::with('realizator','magazine')->get();
		$owerealizator = Owerealizator::all();
		$oweshop = Oweshop::all();
		$oweother = Oweother::all();
		$zarplata = Salary::whereMonth('created_at', Carbon::now()->month)->with('worker')->get();
		$workers = Worker::whereNotIn('id',Salary::select('worker_id')->groupBy('worker_id')->whereMonth('created_at', Carbon::now()->month)->with('worker')->pluck('worker_id'))->get();
        

		$days = [];
		for($i = 0; $i < Worker::count();$i++){
			$days[] = 0;
		}

		return Inertia::render('Profit/Salary',[
			'incomes' => $income,
			'income' => $income1,
			'expenses' => $expense,
			'categories' => $category,
			'users' => $users,
			'owes1' => $owes,
			'zarplata' => $zarplata,
			'workers' => $workers,
			'days' => $days,
			'saldo' => $days
		]);
	}

	public function dolgi(){

		$income1 = Income::sum('sum')-Expense::sum('sum');
		$income = Income::all();		
		$expense = Expense::all();
		$category = Category::all();
		$users = User::all();
		$owes = Owe::with('realizator','magazine')->get();
		$owerealizator = Owerealizator::with('realizator')->get();
		$oweshop = Oweshop::all();
		$oweother = Oweother::all();
		$zarplata = Salary::whereMonth('created_at', Carbon::now()->month)->with('worker')->get();
		$workers = Worker::whereNotIn('id',Salary::select('worker_id')->groupBy('worker_id')->whereMonth('created_at', Carbon::now()->month)->with('worker')->pluck('worker_id'))->get();
        

		$days = [];
		for($i = 0; $i < Worker::count();$i++){
			$days[] = 0;
		}

		return Inertia::render('Profit/Dolgi',[
			'incomes' => $income,
			'income' => $income1,
			'expenses' => $expense,
			'categories' => $category,
			'users' => $users,
			'owes1' => $owes,
			'zarplata' => $zarplata,
			'workers' => $workers,
			'days' => $days,
			'saldo' => $days,
			'owerealizator' => $owerealizator,
			'oweshop' => $oweshop,
			'oweother' => $oweother,
			'sold1' => Assortment::sold()
		]);
	}

	public function giveSalary(Request $request){
		$income = ($worker->getSalary() / 26) * $request->days;
		$totalIncome = ($income - $salary->OSMS - $salary->IPN - $salary->OPV);

		$worker = Worker::find($request->worker);
		$salary = new Salary();
		$salary->worker_id = $request->worker;
		$salary->days = $request->days;
		$salary->income = $income;
		$salary->OSMS = $worker->income * 0.02;
		$salary->OPV = $salary->income * 0.1;
		$salary->IPN = ($salary->income - 42882 - $salary->OPV - $salary->OSMS) * 0.1;
		$salary->total_income = $totalIncome;
		$salary->initial_saldo = $request->saldo;
		$salary->end_saldo = $totalIncome - $request->saldo;


		dd([$income, $totalIncome, $request->all()]);
		
		$salary->save();

		return "Зарплата отправлена!";
	}

	public function sendExpense(Request $request){
		//dd($request->all());

		$month = Month::orderBy('id','desc')->first();
		$worker = Worker::where('name','like', $request->user)->first();	

		if ($request->category == 2){
			$fullname = explode(' ', $request->user);
			$worker = Worker::where('name','like', $fullname[0])->where('surname', 'like', $fullname[1])->first();
			if ($worker != null) {
				$worker->saldo = $worker->saldo + $request->sum;
				$worker->save();
			}
			
			$salary = Salary::where('worker_id', $worker->id)->whereMonth('created_at',$month->month)->whereYear('created_at', Carbon::now()->year)->first();
			if ($salary != null)
			{
				$salary->initial_saldo = $salary->initial_saldo + $request->sum;
				$salary->end_saldo = $salary->end_saldo - $request->sum;
				$salary->save();
			}


		} else if($request->category == 1){
			if(Salary::where('worker_id',$worker->id)->whereMonth('created_at',$month->month)->whereYear('created_at',Carbon::now()->year)->first())
			{
				return ['error' => "Сотрудник уже получал зарплату в этом месяце!"];
			}

			$salary = Salary::where('worker_id',$worker->id)->whereMonth('created_at',Carbon::now()->month)->whereYear('created_at',Carbon::now()->year)->first() ? Salary::where('worker_id',$worker->id)->whereMonth('created_at',Carbon::now()->month)->whereYear('created_at',Carbon::now()->year)->first() : new Salary();
			$salary->worker_id = $worker->id;
			$salary->days = '26';
			$salary->income = $worker->salary;
			$salary->OSMS = $worker->salary * 0.02;
			$salary->OPV = $worker->salary * 0.1;
			$salary->IPN = ($salary->income - 42882 - $salary->OPV - $salary->OSMS)*0.1;
			$salary->total_income = $salary->income - $salary->OSMS - $salary->IPN - $salary->OPV;
			$salary->initial_saldo = 0;
			$salary->end_saldo = $worker->saldo;
			$salary->save();

		}
		
		$expense = new Expense();
		if($request->category == 1){
			$expense->sum = $worker->salary;
		}
		else{
			$expense->sum = $request->sum;
		}
		
		$expense->user = $request->user;
		$expense->category_id = $request->category;
		$expense->description = $request->description ? $request->description : '';
		
		if($request->category == 4)
			$expense->kassa = '4';
		
		$expense->save();


		$zarplata = Salary::whereMonth('created_at',$month->month)->whereYear('created_at',$month->year)->where('finished','0')->with('worker')->get();
		$workers = Worker::whereNotIn('id',Salary::select('worker_id')->where('finished','0')->groupBy('worker_id')->whereMonth('created_at', Carbon::now()->month)->with('worker')->pluck('worker_id'))->get();

		return ['zarplata' => $zarplata,'workers' => $workers,'expense' => $expense];
		
	}

	public function sendIncome(Request $request){
		$income = new Income();
		$income->user = $request->user;
		$income->sum = $request->sum;
		$income->description = $request->description ? $request->description : " ";
		$income->save();

		return $income;
	}

	public function addWorker(Request $request){

		$worker = new Worker();
        
        $worker->name = $request->first_name;
        $worker->surname = $request->last_name;
        $worker->salary = $request->salary;
    
        $worker->save();

        return Redirect::route('profit');
	}

	public function getWorkUsers(){
		return Supplier::select(['name as first_name', 'surname as last_name'])->orderBy('name')->get();
	}

	public function getWorkers(){
		return Worker::select(['name as first_name', 'surname as last_name'])->orderBy('name')->get();
	}

	public function payOwe(Request $request){
		$shop = Oweshop::where('shop','like',$request->shop)->first();
		$shop->paid += $request->amount;
		$shop->save();
	}
	public function dolgStart(Request $request){
		$shop = Oweshop::find($request->id);
		$shop->dolg_start = $request->amount;
		$shop->save();
	}

	public function saveTotalReport(Request $request){
		$report = new Totalreport();
		//dd($request->report);
		$report->kassa = $request->kassa;
        $report->bank_amount = $request->bank_amount;
        $report->freezer = $request->freezer;
        $report->store = $request->store;
        $report->owesrealization = $request->owesrealization;
        $report->workers = $request->workers;
        $report->actives = $request->actives;
        $report->tetrapack = $request->tetrapack;
        $report->fuel = $request->fuel;
        $report->salary = $request->salary;
        $report->save();
	}

	public function updateTotalReport(Request $request){
		$report = Totalreport::find($request->id);
		$report->kassa = $request->kassa;
        $report->bank_amount = $request->bank_amount;
        $report->freezer = $request->freezer;
        $report->store = $request->store;
        $report->owesrealization = $request->owesrealization;
        $report->workers = $request->workers;
        $report->actives = $request->actives;
        $report->tetrapack = $request->tetrapack;
        $report->fuel = $request->fuel;
        $report->salary = $request->salary;
        $report->save();
	}

	public function Report($report){
		$myreport = Totalreport::where('id',$report)->get();
		
		return Inertia::render('Profit/Report',[
			'report' => $myreport
		]);
	}

	public function realizationReport($report){

		return response()->download(storage_path('report.xlsx'));
		
		$myreport = [];
		$assortment = Store::select('type','id','price')->get();
		foreach($assortment as $item){
			$myreport[] = [
				'product' => $item->type,
				'order_amount' => Report::select('order_amount')->where('realization_id',$report)->where('assortment_id',$item->id)->value('order_amount'),
				'amount' => Report::select('amount')->where('realization_id',$report)->where('assortment_id',$item->id)->value('amount'),
				'returned' => Report::select('returned')->where('realization_id',$report)->where('assortment_id',$item->id)->value('returned'),
				'defect' => Report::select('defect')->where('realization_id',$report)->where('assortment_id',$item->id)->value('defect'),
				'defect_sum' => Report::select('defect_sum')->where('realization_id',$report)->where('assortment_id',$item->id)->value('defect_sum'),
				'sold' => Report::select('sold')->where('realization_id',$report)->where('assortment_id',$item->id)->value('sold'),
				'price' => $item->price,
				'sum' => $item->price*Report::select('amount')->where('realization_id',$report)->where('assortment_id',$item->id)->value('amount')
			];
		}
		$defect_sum = array_sum(array_column($myreport,'defect_sum'));
		$total = array_sum(array_column($myreport,'sum'));
		$itog = [
				'product' => 'Итог:',
				'order_amount' => '',
				'amount' => '',
				'returned' =>'',
				'defect' => '',
				'defect_sum' => $defect_sum,
				'sold' => '',
				'price' => '',
				'sum' => $total
		];

		array_push($myreport,$itog);
		
		return Inertia::render('Profit/RealizationReport',[
			'report' => $myreport
		]);
	}

	public function saveSalary(Request $request) {
		//dd($request->all());

		$month = Month::orderBy('id','desc')->first();

		foreach ($request->workers as $key => $worker){
			$myworker = Worker::find($worker['id']);
			$myworker->salary = $worker['salary'];
			$myworker->save();


			if ($request->days[$key] > 0){
				$salary = new Salary();
				$salary->worker_id = $worker['id'];
				$salary->days = $request->days[$key];
				$salary->income = $worker['salary'] * $salary->days / 26;
				
				$salary->OSMS = $salary->income * 0.02;
				$salary->OPV = $salary->income * 0.1;
				$salary->IPN = ($salary->income - 42882 - $salary->OPV - $salary->OSMS) * 0.1;
				$salary->total_income = $request->total_incomes[$key];//$salary->income-$salary->OSMS-$salary->IPN-$salary->OPV;
				$salary->initial_saldo = $worker['saldo'];
				$salary->end_saldo = $salary->total_income - $salary->initial_saldo;
				/*if($salary->total_income-$worker['saldo']>0){
					$salary->end_saldo = 0;
					Worker::find($worker['id'])->update(array('saldo' => 0));
				}else{
					$salary->end_saldo = $worker['saldo']-$salary->total_income;
					Worker::find($worker['id'])->update(array('saldo' => $worker['saldo']-$salary->total_income));
				}*/
				/*$salary->OSMS = $request->OSMS[$key];
				$salary->IPN = $request->IPN[$key];
				$salary->OPV = $request->OPV[$key];
				$salary->total_income = $request->total_income[$key];
				$salary->initial_saldo = $worker['saldo'];
				$salary->end_saldo = $request->end_saldo[$key];*/

				$salary->save();
			}

		}
		/*salary:this.salary,
                days:this.days,
                income:this.income,
                OSMS:this.OSMS,
                IPN:this.IPN,
                OPV:this.OPV,
                total_income:this.total_income,
                initial_saldo:this.initial_saldo,
                end_saldo:this.end_saldo*/
        $zarplata = Salary::with('worker')->where('finished','0')->whereMonth('created_at', $month->month)->whereYear('created_at',$month->year)->get();
		$workers = Worker::whereNotIn('id',Salary::select('worker_id')->where('finished','0')->groupBy('worker_id')->whereMonth('created_at', Carbon::now()->month)->with('worker')->pluck('worker_id'))->get();
		return ['zarplata' => $zarplata, 'workers' => $workers,'message' => "Отчет сохранен"];
	}

	public function endMonth(Request $request) {
		$month = Month::orderBy('id','desc')->first();
		$salaries = Salary::where('finished','0')->whereMonth('created_at', $month->month)->whereYear('created_at',$month->year)->with('worker')->get();

		// dd($salaries->toArray());

		foreach ($salaries as $salary) {
			// dd($salary->worker);
			$salary->worker->saldo = $salary->end_saldo;
			$salary->worker->save();
		}

		Salary::query()->update(array('finished' => 1));

		return "Месяц завершен";
	}

	public function addOstatok(Request $request){
		$ostatok = Ostatok::whereMonth('created_at',Carbon::now()->month)->whereYear('created_at',Carbon::now()->year)->first();
		if(!$ostatok){
			$ostatok = new Ostatok();
			$ostatok->ostatok = $request->ostatok;
			$ostatok->save();
		}else{
			$ostatok->ostatok += $request->ostatok;
			$ostatok->save();
		}

		return [
			'message' => 'отсаток добавлен',
			'ostatok' => $ostatok->ostatok
				];
	}

	public function getRashod($type){
		$month = Month::orderBy('id','desc')->first();
		

		if($type == 0){
			$expense = Expense::whereNotIn('category_id',[4, 5])->with('category')
				->whereMonth('created_at', $month->month)->whereYear('created_at', $month->year)
				->get();
		}
		else{
			$expense = Expense::where('category_id',$type)->with('category')
				->whereMonth('created_at', $month->month)->whereYear('created_at', $month->year)
				->get();
		}
		return Inertia::render('Profit/Rashod',[
			'report' => $expense
		]);
	}

	public function getSalaryMonth(Request $request){
		$salary = Salary::whereMonth('created_at',$request->month)->whereYear('created_at',$request->year)->with('worker')->get();
		return $salary;
	}

	public function getUchet(){
		$category = Category::all();
		$expenses = Expense::orderBy('id','desc')->get();
		return Inertia::render('Profit/Uchet',[
			'categories' => $category,
			'expenses' => $expenses
		]);
	}

	public function getOwesMonth(Request $request){
 $dolgi = [
            [
                'name' => 'Магнум',
                'owe' => Pivot::whereIn('magazine_id',Magazine::where('name','like','%Magnum%')->pluck('id'))->sum('sum'),
                'realizator'=> User::whereIn('id',Magazine::where('name','like','%Magnum%')->pluck('realizator'))->get()

            ],
            [
                'name' => 'Фиркан',
                'owe' => Pivot::whereIn('magazine_id',Magazine::where('name','like','%Фиркан%')->pluck('id'))->sum('sum'),
                'realizator'=> User::whereIn('id',Magazine::where('name','like','%Фиркан%')->pluck('realizator'))->get()
            ],
            [
                'name' => 'Супер-бум',
                'owe' => Pivot::whereIn('magazine_id',Magazine::where('name','like','%Супер-бум%')->pluck('id'))->sum('sum'),
                'realizator'=> User::whereIn('id',Magazine::where('name','like','%Супер-бум%')->pluck('realizator'))->get()
            ],
            [
                'name' => 'ТОО Адема LTD',
                'owe' => Pivot::whereIn('magazine_id',Magazine::where('name','like','%ТОО Адема LTD%')->pluck('id'))->sum('sum'),
                'realizator'=> User::whereIn('id',Magazine::where('name','like','%ТОО Адема LTD%')->pluck('realizator'))->get()
            ],
            [
                'name' => 'ТОО Али Сауда',
                'owe' => Pivot::whereIn('magazine_id',Magazine::where('name','like','%ТОО Али Сауда%')->pluck('id'))->sum('sum'),
                'realizator'=> User::whereIn('id',Magazine::where('name','like','%ТОО Али Сауда%')->pluck('realizator'))->get()
            ],
            [
                'name' => 'ТОО Казына и К',
                'owe' => Pivot::whereIn('magazine_id',Magazine::where('name','like','%ТОО Казына и К%')->pluck('id'))->sum('sum'),
                'realizator'=> User::whereIn('id',Magazine::where('name','like','%ТОО Казына и К%')->pluck('realizator'))->get()
            ],
            [
                'name' => 'ТОО АК NIET GROUP',
                'owe' => Pivot::whereIn('magazine_id',Magazine::where('name','like','%ТОО АК NIET GROUP%')->pluck('id'))->sum('sum'),
                'realizator'=> User::whereIn('id',Magazine::where('name','like','%ТОО АК NIET GROUP%')->pluck('realizator'))->get()
            ],
            [
                'name' => 'ИП Васитова',
                'owe' => Pivot::whereIn('magazine_id',Magazine::where('name','like','%ИП Васитова%')->pluck('id'))->sum('sum'),
                'realizator'=> User::whereIn('id',Magazine::where('name','like','%ИП Васитова%')->pluck('realizator'))->get()
            ],
            [
                'name' => 'ИП Азимбаев Руслан Альбертович',
                'owe' => Pivot::whereIn('magazine_id',Magazine::where('name','like','%ИП Азимбаев Руслан Альбертович%')->pluck('id'))->sum('sum'),
                'realizator'=> User::whereIn('id',Magazine::where('name','like','%ИП Азимбаев Руслан Альбертович%')->pluck('realizator'))->get()
            ],
            [
                'name' => 'ИП Чапанова З.Х.',
                'owe' => Pivot::whereIn('magazine_id',Magazine::where('name','like','%ИП Чапанова З.Х.%')->pluck('id'))->sum('sum'),
                'realizator'=> User::whereIn('id',Magazine::where('name','like','%ИП Чапанова З.Х.%')->pluck('realizator'))->get()
            ],
            [
                'name' => 'АО "Санаторий Манкент"',
                'owe' => Pivot::whereIn('magazine_id',Magazine::where('name','like','%АО "Санаторий Манкент"%')->pluck('id'))->sum('sum'),
                'realizator'=> User::whereIn('id',Magazine::where('name','like','%АО "Санаторий Манкент"%')->pluck('realizator'))->get()
            ],
            [
                'name' => 'ИП Насыров А.',
                'owe' => Pivot::whereIn('magazine_id',Magazine::where('name','like','%ИП Насыров А.%')->pluck('id'))->sum('sum'),
                'realizator'=> User::whereIn('id',Magazine::where('name','like','%ИП Насыров А.%')->pluck('realizator'))->get()
            ],
            [
                'name' => 'ТОО Акбулак 2',
                'owe' => Pivot::whereIn('magazine_id',Magazine::where('name','like','%ТОО Акбулак 2%')->pluck('id'))->sum('sum'),
                'realizator'=> User::whereIn('id',Magazine::where('name','like','%ТОО Акбулак 2%')->pluck('realizator'))->get()
            ],
            [
                'name' => 'ТОО Сауле',
                'owe' => Pivot::whereIn('magazine_id',Magazine::where('name','like','%ТОО Сауле%')->pluck('id'))->sum('sum'),
                'realizator'=> User::whereIn('id',Magazine::where('name','like','%ТОО Сауле%')->pluck('realizator'))->get()
            ],
            [
                'name' => 'ТОО Gramad Retail',
                'owe' => Pivot::whereIn('magazine_id',Magazine::where('name','like','%ТОО Gramad Retail%')->pluck('id'))->sum('sum'),
                'realizator'=> User::whereIn('id',Magazine::where('name','like','%ТОО Gramad Retail%')->pluck('realizator'))->get()
            ],
            [
                'name' => 'Баян Сулу',
                'owe' => Pivot::whereIn('magazine_id',Magazine::where('name','like','%Баян Сулу%')->pluck('id'))->sum('sum'),
                'realizator'=> User::whereIn('id',Magazine::where('name','like','%Баян Сулу%')->pluck('realizator'))->get()
            ],
            [
                'name' => 'ТОО Скиф Трейд',
                'owe' => Pivot::whereIn('magazine_id',Magazine::where('name','like','%ТОО Скиф Трейд%')->pluck('id'))->sum('sum'),
                'realizator'=> User::whereIn('id',Magazine::where('name','like','%ТОО Скиф Трейд%')->pluck('realizator'))->get()
            ],
            [
                'name' => 'ИП Казакбаева Г.А.',
                'owe' => Pivot::whereIn('magazine_id',Magazine::where('name','like','%ИП Казакбаева Г.А.%')->pluck('id'))->sum('sum'),
                'realizator'=> User::whereIn('id',Magazine::where('name','like','%ИП Казакбаева Г.А.%')->pluck('realizator'))->get()
            ],
            [
                'name' => 'ИП Мырзабаев Шифер завод',
                'owe' => Pivot::whereIn('magazine_id',Magazine::where('name','like','%ИП Мырзабаев Шифер завод%')->pluck('id'))->sum('sum'),
                'realizator'=> User::whereIn('id',Magazine::where('name','like','%ИП Мырзабаев Шифер завод%')->pluck('realizator'))->get()
            ],
            [
                'name' => 'ИП Цой Станислав',
                'owe' => Pivot::whereIn('magazine_id',Magazine::where('name','like','%ИП Цой Станислав%')->pluck('id'))->sum('sum'),
                'realizator'=> User::whereIn('id',Magazine::where('name','like','%ИП Цой Станислав%')->pluck('realizator'))->get()
            ],
            [
                'name' => 'ИП Ярмарка Продуктов',
                'owe' => Pivot::whereIn('magazine_id',Magazine::where('name','like','%ИП Ярмарка Продуктов%')->pluck('id'))->sum('sum'),
                'realizator'=> User::whereIn('id',Magazine::where('name','like','%ИП Ярмарка Продуктов%')->pluck('realizator'))->get()
            ],
            [
                'name' => 'ТОО Сункар өнімдері',
                'owe' => Pivot::whereIn('magazine_id',Magazine::where('name','like','%ТОО Сункар өнімдері%')->pluck('id'))->sum('sum'),
                'realizator'=> User::whereIn('id',Magazine::where('name','like','%ТОО Сункар өнімдері%')->pluck('realizator'))->get()
            ],
            [
                'name' => 'ТОО КААN',
                'owe' => Pivot::whereIn('magazine_id',Magazine::where('name','like','%ТОО КААN%')->pluck('id'))->sum('sum'),
                'realizator'=> User::whereIn('id',Magazine::where('name','like','%ТОО КААN%')->pluck('realizator'))->get()
            ],
            [
                'name' => 'ТОО Хадиша Отель',
                'owe' => Pivot::whereIn('magazine_id',Magazine::where('name','like','%ТОО Хадиша Отель%')->pluck('id'))->sum('sum'),
                'realizator'=> User::whereIn('id',Magazine::where('name','like','%ТОО Хадиша Отель%')->pluck('realizator'))->get()
            ],
            [
                'name' => 'ИП Таншолпан',
                'owe' => Pivot::whereIn('magazine_id',Magazine::where('name','like','%ИП Таншолпан%')->pluck('id'))->sum('sum'),
                'realizator'=> User::whereIn('id',Magazine::where('name','like','%ИП Таншолпан%')->pluck('realizator'))->get()
            ],
            [
                'name' => 'ИП Фудмаркет',
                'owe' => Pivot::whereIn('magazine_id',Magazine::where('name','like','%ИП Фудмаркет%')->pluck('id'))->sum('sum'),
                'realizator'=> User::whereIn('id',Magazine::where('name','like','%ИП Фудмаркет%')->pluck('realizator'))->get()
            ],
            [
                'name' => 'ИП Алпамыс',
                'owe' => Pivot::whereIn('magazine_id',Magazine::where('name','like','%ИП Алпамыс%')->pluck('id'))->sum('sum'),
                'realizator'=> User::whereIn('id',Magazine::where('name','like','%ИП Алпамыс%')->pluck('realizator'))->get()
            ],
            [
                'name' => 'ИП Жамал',
                'owe' => Pivot::whereIn('magazine_id',Magazine::where('name','like','%ИП Жамал%')->pluck('id'))->sum('sum'),
                'realizator'=> User::whereIn('id',Magazine::where('name','like','%ИП Жамал%')->pluck('realizator'))->get()
            ],
            [
                'name'=>'ИП Ешжанова',
                'owe' => Pivot::whereIn('magazine_id',Magazine::where('name','like','%ИП Ешжанова%')->pluck('id'))->sum('sum'),
                'realizator'=> User::whereIn('id',Magazine::where('name','like','%ИП Ешжанова%')->pluck('realizator'))->get()
            ],
            [
                'name'=>'ИП Жупар Жупар',
                'owe' => Pivot::whereIn('magazine_id',Magazine::where('name','like','%ИП Жупар Жупар%')->pluck('id'))->sum('sum'),
                'realizator'=> User::whereIn('id',Magazine::where('name','like','%ИП Жупар Жупар%')->pluck('realizator'))->get()
            ],
            [
                'name'=>'ИП НЕМ НЕМ',
                'owe' => Pivot::whereIn('magazine_id',Magazine::where('name','like','%ИП НЕМ НЕМ%')->pluck('id'))->sum('sum'),
                'realizator'=> User::whereIn('id',Magazine::where('name','like','%ИП НЕМ НЕМ%')->pluck('realizator'))->get()
            ],
            [
                'name'=>'ИП Олжас Олжас',
                'owe' => Pivot::whereIn('magazine_id',Magazine::where('name','like','%ИП Олжас Олжас%')->pluck('id'))->sum('sum'),
                'realizator'=> User::whereIn('id',Magazine::where('name','like','%ИП Олжас Олжас%')->pluck('realizator'))->get()
            ],
            [
                'name' => 'ИП Байкуразова Л.Р.',
                'owe' => Pivot::whereIn('magazine_id',Magazine::where('name','like','%ИП Байкуразова Л.Р.%')->pluck('id'))->sum('sum'),
                'realizator'=> User::whereIn('id',Magazine::where('name','like','%ИП Байкуразова Л.Р.%')->pluck('realizator'))->get()
            ],
            [
                'name'=>'ИП Ким Е.В.',
                'owe' => Pivot::whereIn('magazine_id',Magazine::where('name','like','%ИП Ким Е.В.%')->pluck('id'))->sum('sum'),
                'realizator'=> User::whereIn('id',Magazine::where('name','like','%ИП Ким Е.В.%')->pluck('realizator'))->get()
            ],
            [
                'name'=>'ИП Халилов С.',
                'owe' => Pivot::whereIn('magazine_id',Magazine::where('name','like','%ИП Халилов С.%')->pluck('id'))->sum('sum'),
                'realizator'=> User::whereIn('id',Magazine::where('name','like','%ИП Халилов С.%')->pluck('realizator'))->get()
            ],
        ];
		return $dolgi;
	}
	

	public function getCompanyNaks(Request $request) {
		$shop = Oweshop::where('shop', 'like', '%' . $request->company . '%')->first();

		if ($shop != null) {
			return $naks = Nak::where('shop_id', $shop->id)->get();
		}

		return [];
	}
}