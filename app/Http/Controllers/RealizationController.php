<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Realization;
use App\Models\Realizator;
use Illuminate\Support\Facades\Redirect;
use App\Models\Order;
use App\Models\OrderDop;
use App\Models\Store;
use App\Models\User;
use App\Models\Report;
use App\Models\Income;
use App\Models\Magazine;
use App\Models\Pivot;
use App\Models\Assortment;
use App\Models\Tara;
use App\Models\Nak;
use App\Models\Grocery;
use Carbon\Carbon;
use App\Models\Month;
use DB;

/**
 * 
 */
class RealizationController extends Controller
{
		
	public function index(){
		$ids = Realization::selectRaw('max(id) as id, realizator')->groupBy('realizator')->pluck('id');
        $realizators = User::where('position_id', '3')->with('realization', 'magazine')->orderBy('id', 'DESC')->get();
 		$realcount = Realization::selectRaw('count(id) as amount, realizator')->groupBy('realizator')->with('realizator')->get();
 		$realization_count = Realization::where('status', '1')->count();

 		$dop_count = OrderDop::where('status', -1)->distinct('realization_id')->count();
 		// dd($dop_count);

 		$nak_count = Nak::where('finished', '0')->count();
		$assortment = Store::orderBy('num', 'ASC')->get();
        $realizations = Realization::whereIn('id', $ids)->where('status', '<=', 3)->with('order', 'realizator')->get();
        
        // dd($realizations->toArray());

        //$order = User::order();
		
        $order = [];
        $store = Store::orderBy('num', 'asc')->get();

        // dd($ids);

        foreach ($ids as $id) {
        	$real = Realization::whereId($id)->where('status', '<=', 3)->orderBy('id', 'DESC')->first();
        	if ($real == null) continue;

        	$user = User::find($real->realizator);
        	// dd($realization);
        	$assort = [];

        	foreach($store as $item){
        		$assort[] = [
                    'name' => $item->type,
                    'order_amount' => Report::where('realization_id', $id)->where('assortment_id', $item->id)->value('order_amount'), 
                    'amount' => Report::where('realization_id', $id)->where('assortment_id', $item->id)->get(),
                ];

                // dd([$item, $id, $assort]);
            }

            $order[] = [
                'assortment' => $assort,
                'realizator' => $user,
                'status' => $real->status,
                'updated' => $real->updated_at,
                'id' => $real->id,
            ];
        }

        // dd($order);


		$month = Month::where('completed','0')->first();
        if(!$month){
            $month = Month::where('completed','1')->orderBy('id','desc')->first();
            if(!$month){
                $month = new Month();
                $month->month = date('m');
                $month->year = date("Y");
                $month->days = cal_days_in_month(CAL_GREGORIAN, date('m'), date("Y"));
                $month->save();
            }
        }

        $monthes = [
        	1 => 'янв',
        	2 => 'фев',
        	3 => 'мар',
        	4 => 'апр',
        	5 => 'май',
        	6 => 'июн',
        	7 => 'июл',
        	8 => 'авг',
        	9 => 'сен',
        	10 => 'окт',
        	11 => 'ноя',
        	12 => 'дек',
        ];


		// dd(Report::whereYear('created_at', $month->year)
  //                   ->whereMonth('created_at', $month->month)->get()->toArray());

        $reports = Report::whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)->get();

        $tot = [];
        foreach ($reports as $r) {
        	if ($r->assortment_id == 2) {
        		$tot[] = $r->toArray();
        	}
        }
        // dd($tot);

        $data = [
			'order' => $order,
			'realizations' => $realizations,
			'realizators_total' => [
				'total_sum' => Realization::where('status','2')->sum('realization_sum'),
				'total_defect' => Realization::where('status','2')->sum('defect_sum'),
				'average_percent' => Realization::where('status','2')->avg('percent'),
				'income' => Realization::where('status','2')->sum('income'),
			],
			'days' => $month->days,
			'realizators' => $realizators,
			'assortment' => $assortment,
			'reports' => $reports,
			'count' => $realcount,
			'monthes' => $monthes,
			'realization_count' => $realization_count,
			'dop_count' => $dop_count,
			'nak_count' => $nak_count,
			'report1' => Report::where('user_id',Auth::user()->id)->whereRaw('realization_id = (select max(`realization_id`) from reports)')->with('assortment')->get()->toArray(),
			'sold1' => Assortment::sold(),
			'nakladnoe' => Nak::whereMonth('created_at', $month->month)->whereYear('created_at', $month->year)->orderBy('id', 'DESC')->get(),
		];

		// dd($data);

		return Inertia::render('Sales/Index', $data);

		/*$realizations = Realization::with('realizator','status')->get();
		$orders = Realization::groupBy('realizator')->selectRaw('count(id) as total')->pluck('total');
		$realizators = User::where('position_id','3')->with('realization')->get();
		$items = Order::
		dd($realizators);
		$assortment = Store::all();
		return Inertia::render('Sales/Index',[
			'orders' => $orders,
			'realizations' => $realizations,
			'realizators' => $realizators,
			'assortment' => $assortment
		]);*/
	}

	public function payNak(Request $request) {
		$nak = Nak::find($request->id);

		if ($nak != null) {
			$nak->paid = 1;
			$nak->save();

			return Nak::where('user_id',Auth::user()->id)->with('grocery')->get();
		}
	}

	public function sales(){
		return Inertia::render('Realizations/Index');
	}

	public function order(Request $request){
		$myorder = Order::where('realization_id',$request->id)->with('assortment')->get();
		return ['order' => $myorder];
	}

	public function sendOrder(Request $request){
		//dd($request->all());

		$realization_sum = 0;

		DB::beginTransaction();

		$realization = new Realization();
		$realization->realizator = Auth::user()->id;
		$realization->realization_sum = $realization_sum;
		$realization->defect_sum = 0;
		$realization->percent = 10;
		$realization->status = 1;
		$realization->income = $realization_sum/10;
		$realization->save();

		foreach ($request->order as $key => $value) {
			// code...
			
			if($value != 0){
				$order = new Order();
				$order->realization_id = $realization->id;
				$order->assortment = $key;
				$order->order_amount = $value;
				$order->amount = 0;
				$order->save();

				$report = new Report();
				$report->realization_id = $realization->id;
				$report->user_id = Auth::user()->id;
				$report->assortment_id = $key;
				$report->order_amount = $value;
				$report->amount = 0;
				$report->returned = 0;
				$report->defect = 0;
				$report->defect_sum = 0;
				$report->sold = 0;
				$report->save();

				$realization_sum += Store::find($key)->price * $value;

			}
		}

		DB::commit();

		return ['realization' => Realization::where('id',$realization->id)->with('order','realizator')->get(), 'message' => 'Заявка отправлена на обработку'];
	}

	public function updateOrder(Request $request){
		// dd($request->all());

		foreach ($request->order as $key => $value) {
			if ($value != 0) {
				$dop = OrderDop::where('realization_id', $request->realization_id)->where('assortment', $key)->first();

				if ($dop != null) {
					$dop->order_amount = $dop->order_amount + $value;
					$dop->save();
				} else {

					$dop = new OrderDop();
					$dop->realization_id = $request->realization_id;
					$dop->assortment = $key;
					$dop->order_amount = $value;
					$dop->amount = 0;
					$dop->status = -1;
					$dop->save();
				}

			}
		}

		// update report
		foreach ($request->order as $key => $value) {
			if($value > 0){	
				$report = Report::where('realization_id',$request->realization_id)->where('assortment_id',$key)->first();

				if ($report != null) {
					$report->order_amount = $report->order_amount + $value;
					$report->save();	
				} else {
					$order = new Order();
					$order->realization_id = $request->realization_id;
					$order->assortment = $key;
					$order->order_amount = $value;
					$order->amount = 0;
					$order->save();

					$report = new Report();
					$report->realization_id = $request->realization_id;
					$report->user_id = Auth::user()->id;
					$report->assortment_id = $key;
					$report->order_amount = $value;
					$report->amount = 0;
					$report->returned = 0;
					$report->defect = 0;
					$report->defect_sum = 0;
					$report->sold = 0;
					$report->save();
				}

				
			}
		}

		$real = Realization::find($request->realization_id);
		// $real->status = 1;
		$real->updated_at = Carbon::now();
		$real->save();

		$ids = Realization::selectRaw('max(id) as id, realizator')->where('realizator', Auth::user()->id)->groupBy('realizator')->pluck('id');
        
        $realizations = Realization::whereIn('id',$ids)->with('order','realizator')->get();

		return ['realization' => Realization::where('id', $real->id)->with('order','realizator')->get(), 'message' => 'Дополнительная заявка отправлена на обработку'];
	}

	public function getOrder(Request $request){
		$myorder = Order::where('status','1');
		return $myorder;
	}

	public function getRealization(Request $request){
		$myrealization = Realization::
			where('realizator',$request->id)
			->with('order','realizator')
			->where('status', '<', 3)
			->orderBy('id','DESC')->get();

		return $myrealization;
	}

	public function getRealizatorOrder(Request $request){
		$id = Realization::where('realizator',$request->id)->orderBy('id','DESC')->pluck('id')->first();
		$cash = Realization::where('id',$id)->pluck('cash');
		$majit = Realization::where('id',$id)->pluck('majit');
		$sordor = Realization::where('id',$id)->pluck('sordor');
		$realization = Report::where('realization_id', $id)->with('assortment')->get();
		//dd($id);
		$magazines = Pivot::where('realization_id',$id)->get();
		$columns = [];
		foreach($magazines as $item){		
			$isNal = false;

			$columns[] = 
				['magazine' => Magazine::find($item->magazine_id), 'amount' => $item->sum, 'pivot' => $item->id, 'isNal' => $item->cash == 1];
		}
		if(sizeof($columns) == 0){
			$columns[] = ['magazine' => null, 'amount' => null, 'pivot' => null, 'isNal' => false];
		}
		return ['report' => $realization, 'columns' => $columns, 'cash' => $cash, 'majit' => $majit->sum(), 'sordor' => $sordor->sum()];
	}

	public function changeStatus(){
		$users = User::whereIn('id',Realization::where('status','1')->pluck('realizator'))->get();
		Realization::where('status','1')->update(['status' => '2']);
		return $users;
	}

	public function dopStatus(){
		$dops = OrderDop::where('status', -1)->get();
		
		$dopsIds = [];
		foreach ($dops as $dop) {
			$dopsIds[] = $dop->realization_id;
		}

		// dd($dopsIds);

		$realizatorsIds = Realization::whereIn('id', $dopsIds)->pluck('realizator');
		// dd($realizatorsIds);

		$users = User::whereIn('id', $realizatorsIds)->get();
		// dd($users->toArray());

		Realization::where('status','1')->update(['status' => '2']);
		return $users;
	}

	public function setOrderAmount(Request $request){
		$id_realization = 0;

		foreach($request->order as $key => $item) {
			if ($item['order_amount'] > 0) {
				//dd($item['amount'][0]['id']);
				$order = Report::find($item['amount'][0]['id']);
				$order->amount = $item['amount'][0]['amount'];
				// $order->returned = $item['amount'][0]['amount'];
				$order->save();

				// if ($item['order_amount'] < $item['amount'][0]['amount']) {
				// 	$order->amount = $item['order_amount'];
				// 	// $store->amount += $item['order_amount'] - ($item['amount'][0]['amount'] - $item['order_amount']);
				// } else {
				// 	$order->amount = $item['amount'][0]['amount'];
				// 	// $store->amount -= $item['amount'][0]['order_amount'];
				// }

				$store = Store::find($item['amount'][0]['assortment_id']);
				$store->amount += $item['amount'][0]['amount'];
				
				if ($store->tara){
					$tara = Tara::find($store->tara);
					$tara->amount = ($tara->total - $tara->need - $item['amount'][0]['order_amount']) / $tara->inside;
					$tara->need += $item['amount'][0]['order_amount'];
					$tara->save();
				}

				$store->save();
				$id_realization = $item['amount'][0]['realization_id'];
			}
		}

		
		$realization = Realization::find($id_realization);
		$realization->status = 5;
		$realization->save();

		return "Заказ сохранен";
		/*$order = Report::find($request->id);
		$order->amount = $request->amount;
		
		$order->returned += $request->returned;
		$order->save();

		$store = Store::find($order->assortment_id);
		$store->amount -= $order->order_amount;
		
		//отнимаем этикетки и тару по ассортименту
		if($store->tara){
			$tara = Tara::find($store->tara);
			$tara->amount = ($tara->total-$tara->need-$order->order_amount)/$tara->inside;
			$tara->need += $order->order_amount;
			$tara->save();
		}
		$store->save();*/	
	}

	public function setOrderDefect(Request $request){
		$order = Report::find($request->id);
		$order->defect = $request->amount;
		$order->save();
	}

	public function setOrderDefectSum(Request $request){
		$order = Report::find($request->id);
		$order->defect_sum = $request->amount;
		$order->save();
	}

	public function setOrderSold(Request $request){
		$order = Report::find($request->id);
		$order->sold = $request->amount;
		$order->save();

		$realization = Realization::find($order->realization_id);
		$realization->sold += $request->amount;
		$realization->save();
	}

	public function setOrderReturned(Request $request){
		$order = Report::find($request->id);
		$order->returned = $request->amount;
		$order->save();
	}

	public function addReserve(Request $request){
		$store = Store::find($request->assortment);
		$store->amount = $request->amount;
		$store->save();
	}
	public function getOrderByDate(Request $request){

		$date = date_create($request->date);
        date_add($date, date_interval_create_from_date_string('1 day'));
		$ids = Realization::selectRaw('max(id) as id, realizator')->where('realizator',Auth::user()->id)->groupBy('realizator')->pluck('id');
        $realizator = Auth::user();
 		$realcount = Realization::selectRaw('count(id) as amount, realizator')->groupBy('realizator')->with('realizator')->get();

		$assortment = Store::orderBy('num', 'asc')->get();
        $realizations = Realization::whereIn('id',$ids)->whereDate('created_at', date_format($date, 'Y-m-d'))->with('order','realizator')->get();



		return ['realizations' => $realizations];
	}

	public function today(){
		$realizations = Realization::whereDate('created_at', Carbon::today())->where('realizator',Auth::user()->id)->with('realizator')->orderBy('id','DESC')->get();
		return $realizations;
	}

	public function week(){
		$realizations = Realization::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->where('realizator',Auth::user()->id)->with('realizator')->orderBy('id','DESC')->get();
		return $realizations;
	}
	public function month(){
		$realizations = Realization::whereMonth('created_at', Carbon::now()->month)->where('realizator',Auth::user()->id)->with('realizator')->orderBy('id','DESC')->get();

		return $realizations;
	}
	public function year(){
		$realizations = Realization::whereMonth('created_at', Carbon::now()->month)->where('realizator',Auth::user()->id)->with('realizator')->orderBy('id','DESC')->get();
		return $realizations;
	}


	public function todayT(){
	

		$realizators = Realization::selectRaw('sum(realization_sum) as realization_sum, sum(defect_sum) as defect_sum, sum(income) as income,avg(percent) as percent, realizator')->whereDate('created_at', Carbon::today())->groupBy('realizator')->with('realizator')->orderBy('id','DESC')->get();

		return ['realizators' => $realizators,
		'total' =>  [
				'total_sum' => Realization::whereDate('created_at', Carbon::today())->where('status','2')->sum('realization_sum'),
				'total_defect' => Realization::whereDate('created_at', Carbon::today())->where('status','2')->sum('defect_sum'),
				'average_percent' => Realization::whereDate('created_at', Carbon::today())->where('status','2')->avg('percent'),
				'income' => Realization::whereDate('created_at', Carbon::today())->where('status','2')->sum('income'),
			]
		];
	}

	public function weekT(){
		$realizators = Realization::selectRaw('sum(realization_sum) as realization_sum, avg(percent) as percent, sum(defect_sum) as defect_sum, sum(income) as income, realizator')->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->with('realizator')->groupBy('realizator')->orderBy('id','DESC')->get();

		return ['realizators' => $realizators,
			'total' =>  [
				'total_sum' => Realization::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->sum('realization_sum'),
				'total_defect' => Realization::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->sum('defect_sum'),
				'average_percent' => Realization::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->avg('percent'),
				'income' => Realization::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->sum('income'),
			]
		];
	}
	public function monthT(){
		$realizators = Realization::selectRaw('sum(realization_sum) as realization_sum, sum(defect_sum) as defect_sum, sum(income) as income, avg(percent) as percent, realizator')->whereMonth('created_at', Carbon::now()->month)->with('realizator')->groupBy('realizator')->orderBy('id','DESC')->get();

		return ['realizators' => $realizators,'total' => [
				'total_sum' => Realization::whereMonth('created_at', Carbon::now()->month)->sum('realization_sum'),
				'total_defect' => Realization::whereMonth('created_at', Carbon::now()->month)->sum('defect_sum'),
				'average_percent' => Realization::whereMonth('created_at', Carbon::now()->month)->avg('percent'),
				'income' => Realization::whereMonth('created_at', Carbon::now()->month)->sum('income'),
			]];
	}
	public function yearT(){
		$realizators = Realization::selectRaw('sum(realization_sum) as realization_sum, sum(defect_sum) as defect_sum, sum(income) as income, avg(percent) as percent, realizator')->whereYear('created_at', Carbon::now()->year)->with('realizator')->groupBy('realizator')->orderBy('id','DESC')->get();

		return ['realizators' => $realizators,'total' => [
				'total_sum' => Realization::whereYear('created_at', Carbon::now()->year)->sum('realization_sum'),
				'total_defect' => Realization::whereYear('created_at', Carbon::now()->year)->sum('defect_sum'),
				'average_percent' => Realization::whereYear('created_at', Carbon::now()->year)->avg('percent'),
				'income' => Realization::whereYear('created_at', Carbon::now()->year)->sum('income'),
			]];
	}

	public function saveRealization(Request $request){
		// dd($request->all());

		$reports = $request->report;

		foreach($reports as $key => $report){
			$product = Report::find($report['id']);
			//dd(Store::find($product->assortment_id)->price);
			// $product->returned = $product->amount;
			$product->defect_sum = $product->defect * Store::find($product->assortment_id)->price;
			//$product->sold = $product->amount - $product->returned - $product->defect;
			$product->save();

			// $store = Store::find($product->assortment_id);
			// $store->amount = $store->amount - $product->returned;
			// $store->save();
		}

		$realization = Realization::find($request->realization['id']);
		$realization->realization_sum = $request->realization_sum ? $request->realization_sum : 0;
		$realization->bill = $request->bill ? $request->bill : 0;
		$realization->cash = $request->cash ? $request->cash : 0;
		$realization->majit = $request->majit ? $request->majit : 0;
		$realization->sordor = $request->sordor ? $request->sordor : 0;
		$realization->income = $request->income ? $request->income : 0;
		$realization->percent = $request->percent ? $request->percent : 0;
		$realization->defect_sum = $request->defect_sum ? $request->defect_sum : 0;
		$realization->realizator_income = $request->realizator_income ? $request->realizator_income : 0;
		$realization->status = 3;
		$realization->save();

		$columns = [];
		
		foreach($request->columns as $item){
				if($item['magazine'] != null)
				{
					$pivot = $item['pivot'] ? Pivot::find($item['pivot']) : new Pivot();
					$pivot->realization_id = $request->realization['id'];
					//dd($item['magazine']['id']);
					$pivot->magazine_id = $item['magazine']['id'];
					$pivot->sum = $item['amount'];
					$pivot->save();
				}
		}
		$magazines = Pivot::where('realization_id',$realization->id)->get();
		foreach($magazines as $item){		
			$columns[] = 
				['magazine' => Magazine::find($item->magazine_id), 'amount' => $item->sum, 'pivot' => $item->id, 'isNal' => $item->cash == true];
		}
		if(sizeof($columns) == 0){
			$columns[] = ['magazine' => null, 'amount' => null, 'pivot' => null, 'isNal' => false];
		}

		return ['message' => 'отчет сохранен', 'columns' => $columns];
	}

	public function confirmRealization(Request $request){
		$reports = $request->report;
		foreach($reports as $key => $report){
			$product = Report::find($report['id']) ;
			//dd(Store::find($product->assortment_id)->price);
			$product->defect_sum = $product->defect * Store::find($product->assortment_id)->price;

			//$product->sold = $product->amount - $product->returned - $product->defect;
			// $product->returned = $product->returned + $product->amount - $product->sold;

			$product->save();

			$store = Store::find($product->assortment_id);
			$store->amount = $store->amount + $product->returned;
			$store->save();
		}

		$realization = Realization::find($request->realization['id']);
		$realization->realization_sum = $request->realization_sum ? $request->realization_sum : 0;
		$realization->bill = $request->bill ? $request->bill : 0;
		$realization->cash = $request->cash ? $request->cash : 0;
		$realization->majit = $request->majit ? $request->majit : 0;
		$realization->sordor = $request->sordor ? $request->sordor : 0;
		$realization->income = $request->income ? $request->income : 0;
		$realization->realizator_income = $request->realizator_income ? $request->realizator_income : 0;
		$realization->percent = $request->percent ? $request->percent : 0;
		$realization->defect_sum = $request->defect_sum ? $request->defect_sum : 0;
		//$realization->status = 3;
		//$realization->save();

		foreach($request->columns as $item){
			if($item['magazine'] != null)
			{
				$pivot = $item['pivot'] ? Pivot::find($item['pivot']) : new Pivot();
				$pivot->realization_id = $request->realization['id'];
				$pivot->magazine_id = $item['magazine']['id'];
				$pivot->sum = $item['amount'];
				$pivot->save();
			}
			
		}

		$magazines = Pivot::where('realization_id',$realization->id)->get();
		$columns = [];
		foreach($magazines as $item){		
			$columns[] = 
				['magazine' => Magazine::find($item->magazine_id), 'amount' => $item->sum, 'pivot' => $item->id, 'isNal' => $item->cash == true];
		}
		if(sizeof($columns) == 0){
			$columns[] = ['magazine' => null, 'amount' => null, 'pivot' => null, 'isNal' => false];
		}

		if ($request->income > 0) {
			$incomeUser = User::where('id',$request->realization['realizator'])->value('first_name');

			// // remove prev. incomes for same day
			Income::where('user', $incomeUser)->where('description', 'Реализация')->whereDate('created_at', Carbon::today())->delete();

			// create new
			$income = new Income();
			$income->user = $incomeUser;
			$income->sum = $request->income;
			$income->description = "Реализация";
			$income->save();
		}

		$realization->status = 4;
		$realization->save();

		

		return ['message' => 'отчет сохранен', 'columns' => $columns];
	}

	public function update(Request $request){
		//dd($request->realization_id);
		$r = Realization::find($request->id);
		
		$realization = Report::where('realization_id', $r->id)->with('assortment')->get();
		$realizator = User::with('magazine')->find($request->realizator);
		$magazines = Pivot::where('realization_id', $r->id)->get();
		$columns = [];

		foreach($magazines as $item){		
			$columns[] = 
				['magazine' => Magazine::find($item->magazine_id), 'amount' => $item->sum, 'pivot' => $item->id, 'isNal' => $item->cash == true];
		}
		
 		if(sizeof($columns) == 0){
			$columns[] = ['magazine' => null, 'amount' => null, 'pivot' => null, 'isNal' => false,];
		}
		
		return ['report' => $realization,
                'realizator' => $realizator,
                'columns' => $columns];
	}

	public function sold1(Request $request){
		$sold = Assortment::sold1($request->month,$request->year);

		return $sold;
	}

	public function defects(Request $request){
		$deflects = Assortment::defects($request->month,$request->year);

		return $deflects;
	}

	public function naks(Request $request){
		// dd($request->all());

		// return ['ok', $request->month, $request->year];

		$data = Nak::whereMonth('created_at', $request->month)
			->whereYear('created_at', $request->year)
			->orderBy('id', 'DESC')
			->get();

		return $data;
	}

	public function getMyOrder(Request $request){
		$order = User::order();
		$realization_count = Realization::where('status', '1')->count();
		$dop_count = OrderDop::where('status', -1)->distinct('realization_id')->count();

		$myorder = null;

		if (count($order) > $request->size){
			$myorder = $order[$request->size];
		}

		$order = [];
        $store = Store::orderBy('num', 'asc')->get();

        $latest = $request->latest;

		$ids = Realization::selectRaw('max(id) as id, realizator')
			->groupBy('realizator')
			// ->where('id', '>', $latest)
			->pluck('id');
		//dd($request->all());

        foreach ($ids as $id) {
        	$real = Realization::whereId($id)->where('status', '<=', 3)->orderBy('id', 'DESC')->first();
        	if ($real == null) continue;

        	$user = User::find($real->realizator);
        	// dd($realization);
        	$assort = [];

        	foreach($store as $item){
        		$assort[] = [
                    'name' => $item->type,
                    'order_amount' => Report::where('realization_id', $id)->where('assortment_id', $item->id)->value('order_amount'), 
                    'amount' => Report::where('realization_id', $id)->where('assortment_id', $item->id)->get(),
                ];

                // dd([$item, $id, $assort]);
            }

            $order[] = [
                'assortment' => $assort,
                'realizator' => $user,
                'status' => $real->status,
                'updated' => $real->updated_at,
                'id' => $real->id,
            ];
        }

		return [
			'order' => $order,
			'count' => $realization_count,
			'refresh' => $order,
			'dop' => $dop_count,
			'nak' => Nak::where('finished','0')->count(),
		];
	}

	public function getSold(){
		$sold = Assortment::sold();
		return Inertia::render('Sales/Sold',[
			'sold1' => $sold
		]);
	}

	public function getReport(){
		$realizators = User::where('position_id','3')->get();
		return Inertia::render('Sales/Report',[
			'realizators' => $realizators
		]);
	}

	public function getOrders(){
		$order = User::order();
		return Inertia::render('Sales/Orders',[
			'order' => $order
		]);
	}

	public function getRealizators(){
		$realizators = User::where('position_id','3')->get();
		return Inertia::render('Sales/Realizators',[
			'realizators' => $realizators
		]);
	}


	public function declineDop() {
		// get dop orders
		$dops = OrderDop::get();

	 	// remove from report
	 	foreach ($dops as $dop) {
	 		$report = Report::where('realization_id', $dop->realization_id)->where('assortment_id', $dop->assortment)->first();

			if ($report != null) {
				$report->order_amount = $report->order_amount - $dop->order_amount;
				$report->save();	
			}
	 	}
		
		foreach ($dops as $dop) {
			$dop->delete();
		}

		return 'Доп. заявка отклонена';
	}

	public function acceptDop() {
		$dops = OrderDop::get();

		foreach ($dops as $dop) {
			$real = Realization::find($dop->realization_id);
			$real->status = 1;
			$real->updated_at = Carbon::now();
			$real->save();

			// delete
			$dop->delete();
		}



		return 'Доп. заявка принята';
	}
}