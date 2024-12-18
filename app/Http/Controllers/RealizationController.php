<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Realization;
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
use App\Models\Month;
use App\Models\Account;
use App\Models\NakReturn;
use App\Models\Percent;
use App\Models\Oweshop;
use App\Models\PercentStorePivot;
use App\Models\Branch;
use App\Services\RealizationService;
use App\Services\WeightstoreService;
use DB;
use Illuminate\Support\Facades\Http;


class RealizationController extends Controller
{
	public function index(RealizationService $realizationService)
	{
		$ids = $realizationService->getWaitingDistributorsRealizations();

		$month = Month::getCurrent();
        $carbon = Carbon::createFromDate($month->year, $month->month, 1);
        $betweenDate = [
            $carbon->format('Y-m-d'),
            $carbon->endOfMonth()->endOfDay()->format('Y-m-d')
        ];

		$data = [
            'itog' => [],
            'days' => $month->days,
            'currentMonth' => $month->month,
			'order' => $realizationService->getOrders($ids), // 0.51 s
            'assortment' => Store::orderBy('num', 'asc')->get(),
            'count' => $realizationService->quantityOfDistributorsRealizations(), // реализаторы на странице "реализаторы"
			'monthes' => Month::getShortMonthsArray(),
			'pivotPrices' => PercentStorePivot::get(),
			'oweshops' => [], 
            'sold1' => [], //Assortment::soldAll($month->month, $month->year), // 0.11 s
            'realization_count' => Realization::notRead()->notProduced()->count(),
            'realizators' => User::withTrashed()->isDistributor()->orderBy('first_name', 'ASC')->get(), // реализаторы на странице "итоги заявок"
			'realizations' => [],
			'realizators_total' => ['total_sum' =>0, 'total_defect' => 0, 'average_percent' => 0, 'income' => 0],
			'report1' => [],
			// 'realizations' => Realization::whereIn('id', $ids)->with('order', 'realizator')->get(),
			// 'realizators_total' => [
			// 	'total_sum' => Realization::where('status', '2')->sum('realization_sum'),
			// 	'total_defect' => Realization::where('status', '2')->sum('defect_sum'),
			// 	'average_percent' => Realization::where('status', '2')->avg('percent'),
			// 	'income' => Realization::where('status', '2')->sum('income'),
			// ],
			// 'report1' => Report::where('user_id', auth()->id()) // 0.2 s
            //     ->whereRaw('realization_id = (select max(`realization_id`) from reports)')
            //     ->with('assortment')
            //     ->get()
            //     ->toArray(),
            'dop_count' => OrderDop::where('status', -1)
                ->distinct('realization_id')
                ->count(),
            'nak_count' => Nak::notFinishedAmount(),
			'nakladnoe' => Nak::whereBetween('created_at', $betweenDate)
                ->orderBy('id', 'ASC')
                ->get(),
		];

		return Inertia::render('Sales/Index', $data);
	}

	public function salesByDate(Request $request, RealizationService $realizationService)
	{	
		$carbon = Carbon::parse($request->date);
		$ids = $realizationService->getRealizationsByDate($request->date);

		$data = [
			'order' => $realizationService->getOrders($ids), 
		];

		return $data;
	}

	public function getItogData(Request $request)
	{
		$output = [];
		$monthOutput = [];

		$now = Carbon::now();

		$month = $request->month;
		$realizator_id = $request->realizator_id;
		$year = $request->year ? $request->year : $now->year;

		if($month && $year) {
			$now = Carbon::createFromDate($year, $month, 1);
		}

		$realizations = Realization::query()
			->with(['reports'])
			->whereYear('created_at', '=', $year)
			->whereMonth('created_at', '=', $month);

		if($realizator_id) {
			$realizations->where('realizator', $realizator_id);
		}

		$realizations = $realizations->get();

		$maxDays = $now->daysInMonth;

		$products = Store::orderBy('num', 'asc')->get();

		for ($i = 0; $i < $maxDays; $i++) {
			$output[$i] = [
				'name' => '',
				'number' => '',
				'monthTotal' => '',
			];

			foreach ($products as $product) {
				$output[$i][$product->id] = [
					'name' => $product->type,
					'number' => 0,
					'monthTotal' => 0,
				];

				$monthOutput[$product->id] = 0;
			}
		}

		foreach ($realizations as $real) {
			foreach ($real->reports as $report) {
				$reportDay = $report->created_at->format('j');

				if (isset($output[$reportDay - 1][$report->assortment_id])) {
					$output[$reportDay - 1][$report->assortment_id]['number'] += $report->order_amount;
					$monthOutput[$report->assortment_id] += $report->order_amount;
				}
			}
		}

		return ['data' => $output, 'total' => $monthOutput, 'days' => $maxDays];
	}

	public function getAvansReport(Request $request)
	{
		$id = $request->id;

		$myreport = [
            [
                "product" => "Продукт",
                "order_amount" => "Заявка",
                "amount" => "Отпущено",
                "returned" => "Возврат",
                "defect" => "Брак",
                "defect_sum" => "Брак на сумму",
                "sold" => "Продано",
                "price" => "СТР Цена",
                "sum" => "Сумма",
                "sum2" => ""
            ]
        ];

		$assortment = Store::select('type', 'id', 'price')->orderBy('num', 'asc')->get();

		$realization = Realization::find($id);

        $a = microtime(true);

        $prepReports = Report::where('realization_id', $id)->get();


		foreach ($assortment as $item) {

            $prepReport = $prepReports->where('assortment_id', $item->id)->first();

			$orderAmount = $prepReport ? $prepReport->order_amount : 0;
			$amount = $prepReport ? $prepReport->amount : 0;
			$defect = $prepReport ? $prepReport->defect : 0;
			$sold = $prepReport ? $prepReport->sold : 0;
			// $returned = $prepReport ? $prepReport->returned : 0;

			$defectSum = $defect * $this->_getPivotPrice($realization->percent, $item);

			$price = $this->_getPivotPrice($realization->percent, $item);

			$sum = $this->_getPivotPrice($realization->percent, $item) * ($sold - $defect);

			$myreport[] = [
				'product' => $item->type,
				'order_amount' => $orderAmount,
				'amount' => $amount,
				'returned' => $amount - $sold,
				'defect' => $defect,
				'defect_sum' => $defectSum,
				'sold' => $sold - $defect,
				'price' => $price,
				'sum' => $sum,
                'sum2'=> ''
			];
		}

		// total defect sum
		$totalDefectSum = array_sum(array_column($myreport, 'defect_sum'));

		// total sum
		$totalSum = array_sum(array_column($myreport, 'sum'));

		// total realization sum
		$realizationSum = 0;

		$magazines = Pivot::where('realization_id', $realization->id)->get();
		foreach ($magazines as $item) {
			if ($item->cash == 0) {
				$realizationSum += $item->sum;
			}
		}

		// total cash
		$totalCash = $totalSum - $realizationSum;

		// majit
		$majit = $realization->majit != null ? $realization->majit : 0;

		// za uslugi
		$zaUslugi = ($totalSum - $realizationSum) * $realization->percent / 100;

		// k oplate
		$kOplate = ($totalSum - $realizationSum - $majit) - ($totalSum - $realizationSum) * $realization->percent / 100;

        $line = [
            'product' => '',
            'order_amount' => '',
            'amount' => '',
            'returned' => '',
            'defect' => '',
            'defect_sum' => '',
            'sold' => '',
            'price' => '',
            'sum' => '',
            'sum2' => '',
        ];

        // $itogTotalSum['sum'] = $totalSum;
        // $itogRealizationSum['sum'] = $realizationSum;
        //$itogMajit['sum'] = $majit;

        $linesAfterProducts = 13;

        for($i = 1; $i <= $linesAfterProducts; $i++) {
            ${"line" . $i} = $line;
        }

        $line1['product'] = 'Возврат накл';
        $line1['sum2'] = '↓ ИТОГ';

        $line2['defect_sum'] = $totalDefectSum;

        $line4['defect'] = 'Сумма реализации';

        $line5['defect_sum'] = $realizationSum;

		$line9['defect'] = 'НАЛ';
		$line9['defect_sum'] = $totalCash;

		$line11['defect'] = 'общ   %';
		$line11['defect_sum'] = $zaUslugi;

		$line13['defect'] = 'К оплате';
		$line13['defect_sum'] = $kOplate;

        for($i = 1; $i <= $linesAfterProducts; $i++) {
            $myreport[] = ${"line" . $i};
        }

		// naks
		$fields = [
			"Продукт" => "product",
			"Заявка" => "order_amount",
			"Отпущено" => "amount",
			"Возврат" => "returned",
			"Обмен брак" => "defect",
			"Брак на сумму" => "defect_sum",
			"Продано" => "sold",
			"Цена" => "price",
			"Сумма" => "sum",
		];

      
		return [
			'fields' => $fields,
			'data' => $myreport,
		];
	}

	public function payNak(Request $request)
	{
		$nak = Nak::find($request->id);

		if ($nak != null) {
			$nak->paid = 1;
			$nak->save();

			return Nak::where('user_id', Auth::user()->id)->with('grocery')->orderBy('created_at', 'DESC')->get();
		}
	}

	public function sales()
	{
		return Inertia::render('Realizations/Index');
	}

	public function order(Request $request)
	{
		$myorder = Order::where('realization_id', $request->id)->with('assortment')->get();
		return ['order' => $myorder];
	}

	/**
	 * Подать новую заявку
	 * делает реализатор
	 */
	public function sendOrder(Request $request)
	{
		$MKT = 2;
		$account = Account::find($MKT);
		
		$timeToday = Carbon::today()->setTimezone('Asia/Karachi')->setTimeFromTimeString($account->request_time);
		$now = Carbon::now()->setTimezone('Asia/Karachi');

		\Log::info('Проверка времени' ,[
			'now' => $now,
			'timeToday' => $timeToday,
			'$now->greaterThan($timeToday)' => $now->greaterThan($timeToday),
		]);


		// Check if the time has passed
		if ($now->greaterThan($timeToday)) {
			return response()->json([
				'message' => "Время подачи заявок $timeToday прошел и заявки сегодня не принимаются."
			], 400); 
		} 

		// SAVE ORDER
		$realization_sum = 0;

		DB::beginTransaction();

		$realization = new Realization();
		$realization->realizator = Auth::user()->id;
		$realization->realization_sum = $realization_sum;
		$realization->defect_sum = 0;
		$realization->percent = Percent::find($request->percent)->amount;
		$realization->status = 1;
		$realization->income = $realization_sum / 10;
		$realization->save();

		foreach ($request->order as $key => $value) {
			// code...

			// if($value != 0) {
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
			// }
		}

		DB::commit();

		return [
			'realization' => Realization::where('id', $realization->id)->with('order', 'realizator')->get(),
			'message' => 'Заявка отправлена на обработку'
		];
	}

	public function updateOrder(Request $request)
	{	
		foreach ($request->order as $key => $value) {
			// if ($value != 0) {
			$dop = OrderDop::where('realization_id', $request->realization_id)->where('assortment', $key)->first();

			if($dop && $dop->status !== -1) {
				$dop->delete();
				$dop = null;
			}

			if ($dop != null) {
				$dop->order_amount = $dop->order_amount + $value;
				$dop->status = -1;
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

			// }
		}

		// update report
		foreach ($request->order as $key => $value) {
			// if($value > 0){
			$report = Report::where('realization_id', $request->realization_id)->where('assortment_id', $key)->first();

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
			// }
		}

		$real = Realization::find($request->realization_id);
		// $real->status = 1;
		$real->updated_at = Carbon::now();
		$real->save();

		$ids = Realization::selectRaw('max(id) as id, realizator')->where('realizator', Auth::user()->id)->groupBy('realizator')->pluck('id');

		$realizations = Realization::whereIn('id', $ids)->with('order', 'realizator')->get();

		return ['realization' => Realization::where('id', $real->id)->with('order', 'realizator')->get(), 'message' => 'Дополнительная заявка отправлена на обработку'];
	}

	public function deleteRealization(Request $request)
	{
		Realization::where('id', $request->id)->delete();
		Order::where('realization_id', $request->id)->delete();
		Report::where('realization_id', $request->id)->delete();
	}

	public function getOrder(Request $request)
	{
		$myorder = Order::where('status', '1');
		return $myorder;
	}

	public function getRealization(Request $request)
	{
		$myrealization = Realization::where('realizator', $request->id)
			->with('order', 'realizator')
			->where('is_released', 1)
			->orderBy('id', 'ASC')->get();

		return $myrealization;
	}

	// avans report table first request from 2
	public function getRealizatorOrder(Request $request)
	{	
		$id = Realization::where('realizator', $request->id)
			->where('is_accepted', 0)
			->orderBy('id', 'ASC')
			->pluck('id')
			->first();
		
		$id = $request->realization_id ?? $id;
		
		if ($id == null) {
			return [
				'nakReturns' => [],
				'real' => null,
				'percent' => null,
				'report' => [],
				'columns' => [],
				'cash' => 0,
				'majit' => 0,
				'sordor' => 0,
				'realizationNaks' => [],
			];
		}

		

		$real = Realization::where('id', $id)->first();
		$percent = Percent::where('amount', intval($real->percent))->first();
		$cash = Realization::where('id', $id)->pluck('cash');
		$majit = Realization::where('id', $id)->pluck('majit');
		$sordor = Realization::where('id', $id)->pluck('sordor');

		$realization = Report::where('realization_id', $id)->with('assortment')->get();
		$realization = $realization->sortBy(fn($item, $key) => $item->assortment->num);

		$realizationNaks = Nak::where('realization_id', $id)->with(['shop'])->get();

		$magazines = Pivot::where('realization_id', $id)->get();

		$columns = [];

		foreach ($magazines as $item) {
			$columns[] = [
				'magazine' => Branch::whereId($item->magazine_id)->first(),
				'amount' => $item->sum,
				'pivot' => $item->id,
				'isNal' => $item->cash == 1,
				'is_return' => $item->is_return,
				'nak' => null,
				'nak_id' => $item->nak_id,
			];
		}

		foreach($realizationNaks as $nak) {
			$pivot = $magazines->where('nak_id', $nak->id)->first();
			$nak->sum = $pivot ? $pivot->sum : 0;
		}

		$nakReturns = NakReturn::query()
			->with('oweshop')
			->where('realization_id', $real->id)
			->get();

		return [
			'nakReturns' => $nakReturns,
			'real' => $real,
			'percent' => $percent,
			'report' => $realization->values(),
			'columns' => $columns,
			'cash' => $cash,
			'majit' => $majit->sum(),
			'sordor' => $sordor->sum(),
			'realizationNaks' => $realizationNaks,
			'magazine' => User::withTrashed()->find($real->realizator)->branches()->orderBy('name')->get()->unique('id'),
		];
	}

	public function changeStatus()
	{
		$users = User::whereIn('id', Realization::where('is_read', 0)->pluck('realizator'))->get();
		Realization::where('is_read', 0)->update(['is_read' => 1]);
		return $users;
	}

	public function dopStatus()
	{
		$realizationIds = OrderDop::where('status', -1)->get()->pluck('realization_id');

		$realizators = Realization::with('dops.assortment', 'realizator')->whereIn('id', $realizationIds)->get();

		Realization::where('is_read', 0)->update(['is_read' => 1]);

		return $realizators;
	}

	public function setOrderAmount(Request $request)
	{	
	
		/****************************
		 * 1. Validate realization_id
		 */
		try {
			$realization_ID = $request->order[0]['amount'][0]['realization_id'];
		} catch(\Exception $e) {
			return response('Не найден номер реализации', 500);
		}

		$real = Realization::find($realization_ID);
		if(!$real) {
			return response('Не найдена реализация', 404);
		}

		/****************************
		 * 2. Цикл продуктов
		 */
	
		foreach ($request->order as $key => $item) {

			$record = $item['amount'][0];

			$order = Report::find($record['id']);



			if($item['amount'] < 0) {
				continue;
			}


			
			
			/****************************
			 * 2.1 Находим отчет
			 */
			if(!$order) {
				$order = new Report();
				$order->realization_id = $realization_ID;
				$order->user_id = $real->realizator;
				$order->assortment_id = $record['assortment_id'];
				$order->order_amount = $item['amount'];
				$order->amount = 0;
				$order->returned = 0;
				$order->defect = 0;
				$order->defect_sum = 0;
				$order->sold = 0;
			}
			
			/****************************
			 * 2.2 Берем тары для продукции
			 */
			$store = Store::find($record['assortment_id']);

			$pivots = DB::table('tara_store')->where('store_id', $store->id)->get();

			foreach ($pivots as $pivot) {
				$tara = Tara::find($pivot->tara_id);
				if (!$tara) continue;

				$tara->amount = $tara->amount - $record['amount'] * $pivot->need;
				if($order) $tara->amount = $tara->amount + $order->amount * $pivot->need;
				$tara->save();
			}

			/****************************
			 * 2.3 Создаем продукт из весового склада
			 */
			(new WeightstoreService)->createProduct($record['assortment_id'], $record['amount']);


			/****************************
			 * 2.4 Перемещаем продукт в склад
			 */
			$store->amount += $record['amount']; // @TODO Ошибка При доп заявке отнимается еще раз. Пример: заявка на 100 отпущено 100. Потом доп заявка еще на 10. Вместо 10 в склад падает 110.
			if($order) $store->amount -= $order->amount;
			$store->save();
			

			// 2.5 Сохраняем отчет
			$order->amount = $record['amount'];
			$order->save();

		}

		/****************************
		 * 3. Save realization
		 */
		$realization = Realization::find($realization_ID);
		$realization->status = 5;
		$realization->is_produced = 1;
		$realization->save();

		return "Продукция изготовлена и перемещена в склад";
	}

	public function setOrderDefect(Request $request)
	{
		$order = Report::find($request->id);
		$order->defect = $request->amount;
		$order->save();
	}

	public function setOrderDefectSum(Request $request)
	{
		$order = Report::find($request->id);
		$order->defect_sum = $request->amount;
		$order->save();
	}

	public function setOrderSold(Request $request)
	{
		$order = Report::find($request->id);
		$order->sold = $request->amount;
		$order->save();

		$realization = Realization::find($order->realization_id);
		$realization->sold += $request->amount;
		$realization->save();
	}

	public function setOrderReturned(Request $request)
	{
		$order = Report::find($request->id);
		$order->returned = $request->amount;
		$order->save();
	}

	public function addReserve(Request $request)
	{
		$store = Store::find($request->assortment);
		$store->amount = $request->amount;
		$store->save();
	}

	public function getOrderByDate(Request $request)
	{

		$date = date_create($request->date);
		date_add($date, date_interval_create_from_date_string('1 day'));
		$ids = Realization::selectRaw('max(id) as id, realizator')->where('realizator', Auth::user()->id)->groupBy('realizator')->pluck('id');
		$realizator = Auth::user();
		$realcount = Realization::selectRaw('count(id) as amount, realizator')->groupBy('realizator')->with('realizator')->get();

		$assortment = Store::orderBy('num', 'asc')->get();
		$realizations = Realization::whereIn('id', $ids)->whereDate('created_at', date_format($date, 'Y-m-d'))->with('order', 'realizator')->get();



		return ['realizations' => $realizations];
	}

	public function today()
	{
		$realizations = Realization::whereDate('created_at', Carbon::today())->where('realizator', Auth::user()->id)->with('realizator')->orderBy('id', 'ASC')->get();
		return $realizations;
	}

	public function week()
	{
		$realizations = Realization::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->where('realizator', Auth::user()->id)->with('realizator')->orderBy('id', 'ASC')->get();
		return $realizations;
	}

	public function month()
	{
		$realizations = Realization::whereMonth('created_at', Carbon::now()->month)->where('realizator', Auth::user()->id)->with('realizator')->orderBy('id', 'ASC')->get();

		return $realizations;
	}
	
	public function year()
	{
		$realizations = Realization::whereMonth('created_at', Carbon::now()->month)->where('realizator', Auth::user()->id)->with('realizator')->orderBy('id', 'ASC')->get();
		return $realizations;
	}


	public function todayT()
	{


		$realizators = Realization::selectRaw('sum(realization_sum) as realization_sum, sum(defect_sum) as defect_sum, sum(income) as income,avg(percent) as percent, realizator')->whereDate('created_at', Carbon::today())->groupBy('realizator')->with('realizator')->orderBy('id', 'ASC')->get();

		return [
			'realizators' => $realizators,
			'total' =>  [
				'total_sum' => Realization::whereDate('created_at', Carbon::today())->where('status', '2')->sum('realization_sum'),
				'total_defect' => Realization::whereDate('created_at', Carbon::today())->where('status', '2')->sum('defect_sum'),
				'average_percent' => Realization::whereDate('created_at', Carbon::today())->where('status', '2')->avg('percent'),
				'income' => Realization::whereDate('created_at', Carbon::today())->where('status', '2')->sum('income'),
			]
		];
	}

	public function weekT()
	{
		$realizators = Realization::selectRaw('sum(realization_sum) as realization_sum, avg(percent) as percent, sum(defect_sum) as defect_sum, sum(income) as income, realizator')->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->with('realizator')->groupBy('realizator')->orderBy('id', 'ASC')->get();

		return [
			'realizators' => $realizators,
			'total' =>  [
				'total_sum' => Realization::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->sum('realization_sum'),
				'total_defect' => Realization::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->sum('defect_sum'),
				'average_percent' => Realization::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->avg('percent'),
				'income' => Realization::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->sum('income'),
			]
		];
	}
	public function monthT()
	{
		$realizators = Realization::selectRaw('sum(realization_sum) as realization_sum, sum(defect_sum) as defect_sum, sum(income) as income, avg(percent) as percent, realizator')->whereMonth('created_at', Carbon::now()->month)->with('realizator')->groupBy('realizator')->orderBy('id', 'ASC')->get();

		return ['realizators' => $realizators, 'total' => [
			'total_sum' => Realization::whereMonth('created_at', Carbon::now()->month)->sum('realization_sum'),
			'total_defect' => Realization::whereMonth('created_at', Carbon::now()->month)->sum('defect_sum'),
			'average_percent' => Realization::whereMonth('created_at', Carbon::now()->month)->avg('percent'),
			'income' => Realization::whereMonth('created_at', Carbon::now()->month)->sum('income'),
		]];
	}
	public function yearT()
	{
		$realizators = Realization::selectRaw('sum(realization_sum) as realization_sum, sum(defect_sum) as defect_sum, sum(income) as income, avg(percent) as percent, realizator')->whereYear('created_at', Carbon::now()->year)->with('realizator')->groupBy('realizator')->orderBy('id', 'ASC')->get();

		return ['realizators' => $realizators, 'total' => [
			'total_sum' => Realization::whereYear('created_at', Carbon::now()->year)->sum('realization_sum'),
			'total_defect' => Realization::whereYear('created_at', Carbon::now()->year)->sum('defect_sum'),
			'average_percent' => Realization::whereYear('created_at', Carbon::now()->year)->avg('percent'),
			'income' => Realization::whereYear('created_at', Carbon::now()->year)->sum('income'),
		]];
	}

	public function saveRealization(Request $request)
	{
	
		$realization = Realization::find($request->realization_id);

		if ($realization->is_produced == 0) {
			return ['status' => 'error', 'message' => 'Ошибка: продукция еще не изготовлена'];
		}

		$reports = $request->report;

		foreach ($reports as $key => $report) {
			$product = Report::find($report['id']);

			$store = Store::find($product->assortment_id);

			if($realization->is_released === 0) {
				$store->amount -= $report['amount']; // выгружаем со склада
			} else {
				$store->amount -= $report['amount'] ; // выгружаем со склада
				$store->amount += $product->amount; // возвращаем предыдущие на склад
			}
			
			$store->save();

			$product->defect = $report['defect'];
			$product->amount = $report['amount'];
			$product->returned = $report['returned'];
			$product->save();
		}

		// save 
		$realization->realization_sum = $request->realization_sum ? $request->realization_sum : 0;
		$realization->bill = $request->bill ? $request->bill : 0;
		$realization->cash = $request->cash ? $request->cash : 0;
		$realization->majit = $request->majit ? $request->majit : 0;
		$realization->sordor = $request->sordor ? $request->sordor : 0;
		$realization->income = $request->income ? $request->income : 0;
		$realization->defect_sum = $request->defect_sum ? $request->defect_sum : 0;
		$realization->realizator_income = $request->realizator_income ? $request->realizator_income : 0;
		$realization->status = 3;
		$realization->is_released = 1;
		$realization->save();

		$columns = [];

		foreach ($request->columns as $item) {
			if ($item['magazine'] != null) {
				$pivot = $item['pivot'] ? Pivot::find($item['pivot']) : new Pivot();
				$pivot->realization_id = $request->realization['id'];
				$pivot->magazine_id = $item['magazine']['id'];
				$pivot->sum = $item['amount'];
				$pivot->save();
			}
		}

		$magazines = Pivot::where('realization_id', $realization->id)->get();
		foreach ($magazines as $item) {
			$columns[] =
				[
					'magazine' => Magazine::find($item->magazine_id),
					'amount' => $item->sum,
					'pivot' => $item->id,
					'isNal' => $item->cash == true,
					'nak' => null,
				];
		}

		if (sizeof($columns) == 0) {
			$columns[] = ['magazine' => null, 'amount' => null, 'pivot' => null, 'isNal' => false, 'nak' => null];
		}

		return ['status' => 'ok', 'message' => 'Товар отгружен', 'columns' => $columns, 'realization' => $realization];
	}	

	private function saveIncome(Realization $realization, $cash) {

		$realizator = User::find($realization->realizator);
		$cash_to_kassa = $cash * ( (100 - $realization->percent) / 100);

		$incomeData = [
			'user' => $realizator ? $realizator->first_name . ' ' . $realizator->last_name : 'Реализатор',
			'sum' => $cash_to_kassa,
			'description' => 'Наличные при отгрузке товаров в авансовом отчете',
			'realization_id' => $realization->id
		];

		if($realization->income_id) {
			$income_id = $realization->income_id;
			Income::where('id', $realization->income_id)->update($incomeData);
		} else {
			$income = Income::create($incomeData);
			$income_id = $income->id;
		}


		return $income_id;

	}

	private function _getPivotPrice($percentAmount, $item)
	{
		$pivotPrices = PercentStorePivot::get();
		$percent = Percent::where('amount', $percentAmount)->first();

		foreach ($pivotPrices as $pivot) {
			if ($pivot->percent_id == $percent->id && $pivot->store_id == $item->id) {
				return $pivot->price;
			}
		}

		return 0;
	}

	public function confirmRealization(Request $request)
	{
		$realization = Realization::find($request->realization['id']);

		if ($realization->is_produced != 1 && $realization->is_released != 1) {
			return ['status' => 'error', 'message' => 'Ошибка: сначала изготовьте продукцию и отгрузите товар'];
		}

		$reports = $request->report;
		
		foreach ($reports as $key => $report) {
			
			$product = Report::find($report['id']);
			$product->defect_sum = $product->defect * $this->_getPivotPrice(intval($request->real['percent']), Store::find($product->assortment_id));

			// перерасчет на складе
			$store = Store::find($product->assortment_id);
			$store->amount += $report['returned']; // Возврат идет в склад 
			// $store->amount -= $report['amount']; // Отпущено уходит со склада 
			$store->save();

			// новая отгрузка
			$product->amount = $report['amount'];

			// случай когда не было ни продаж, ни брака/обмена
			if ($product->sold == 0 && $product->defect == 0) {
				$product->returned = $product->amount;
			}

			$product->save();
		}


		// get income and update
		if($request->cash > 0 || $realization->income_id ) {

			$realization->income_id = $this->saveIncome($realization, $request->cash);

		}
		
		$realization->realization_sum = $request->realization_sum ? $request->realization_sum : 0;
		$realization->bill = $request->bill ? $request->bill : 0;
		$realization->cash = $request->cash ? $request->cash : 0;
		$realization->majit = $request->majit ? $request->majit : 0;
		$realization->sordor = $request->sordor ? $request->sordor : 0;
		$realization->income = $request->income ? $request->income : 0;
		$realization->realizator_income = $request->realizator_income ? $request->realizator_income : 0;
		$realization->defect_sum = $request->defect_sum ? $request->defect_sum : 0;

		foreach ($request->columns as $item) {
			if ($item['magazine'] != null) {
				$pivot = $item['pivot'] ? Pivot::find($item['pivot']) : new Pivot();
				$pivot->realization_id = $request->realization['id'];
				$pivot->magazine_id = $item['magazine']['id'];
				$pivot->sum = $item['amount'];
				$pivot->save();
			}
		}

		$magazines = Pivot::where('realization_id', $realization->id)->get();
		$columns = [];

		foreach ($magazines as $item) {
			$columns[] = [
				'magazine' => Magazine::find($item->magazine_id),
				'amount' => $item->sum,
				'pivot' => $item->id,
				'isNal' => $item->cash == true,
				'nak' => null,
			];
		}

		if (sizeof($columns) == 0) {
			$columns[] = ['magazine' => null, 'amount' => null, 'pivot' => null, 'isNal' => false, 'nak' => null,];
		}

		$realization->status = 4;
		$realization->is_produced = 1;
		$realization->is_released = 1;
		$realization->is_accepted = 1;
		$realization->save();

		return ['status' => 'ok', 'message' => 'Отчет принят и сохранен', 'columns' => $columns];
	}
	
	public function update(Request $request)
	{

		// $r = Realization::find($request->id);

		// $realization = Report::where('realization_id', $r->id)->with('assortment')->get();
		// $realizator = User::with('magazine')->find($request->realizator);
		// $magazines = Pivot::where('realization_id', $r->id)->get();
		// $columns = [];

		// foreach ($magazines as $item) {
		// 	$columns[] =
		// 		[
		// 			'magazine' => Magazine::find($item->magazine_id),
		// 			'amount' => $item->sum,
		// 			'pivot' => $item->id,
		// 			'isNal' => $item->cash == true,
		// 			'nak' => null,
		// 		];
		// }

		// if (sizeof($columns) == 0) {
		// 	$columns[] = [
		// 		'magazine' => null,
		// 		'amount' => null,
		// 		'pivot' => null,
		// 		'isNal' => false,
		// 		'nak' => null,
		// 	];
		// }

		// return [
		// 	'report' => $realization,
		// 	'realizator' => $realizator,
		// 	'columns' => $columns
		// ];

		$id = $request->id;
		$realizator = User::with('magazine')->find($request->realizator);

		$real = Realization::where('id', $request->id)->first();
	
		$percent = Percent::where('amount', intval($real->percent))->first();

		$cash = Realization::where('id', $id)->pluck('cash');
		$majit = Realization::where('id', $id)->pluck('majit');
		$sordor = Realization::where('id', $id)->pluck('sordor');
		$realization = Report::where('realization_id', $id)->with('assortment')->get();

		$realizationNaks = Nak::where('realization_id', $id)->with(['shop'])->get();

	
		$magazines = Pivot::where('realization_id', $id)->get();
		$columns = [];

		foreach ($magazines as $item) {
			

			$columns[] =
				[
					'magazine' => Branch::whereId($item->magazine_id)->first(),
					'amount' => $item->sum,
					'pivot' => $item->id,
					'isNal' => $item->cash == 1,
					'is_return' => $item->is_return,
					'nak' => null,
				];
		}


		if (count($columns) <= 0) {
			$columns[] = ['magazine' => null, 'amount' => null, 'pivot' => null, 'isNal' => false, 'nak' => null,];
		}

		$nakReturns = NakReturn::query()
			->with('oweshop')
			->where('realization_id', $real->id)
			->get();


		return [
			'nakReturns' => $nakReturns,
			'real' => $real,
			'percent' => $percent,
			'report' => $realization,
			'columns' => $columns,
			'cash' => $cash,
			'majit' => $majit->sum(),
			'sordor' => $sordor->sum(),
			'realizationNaks' => $realizationNaks,
			'magazine' => User::find($real->realizator)->branches()->orderBy('name')->get(),
			'realizator' => $realizator,
		];
	}

	/**
	 * Отчет продаж
	 */
	public function sold1(Request $request)
	{		
		$month = $request->month;
		$year = $request->year;
		$period = $request->period;

		if (empty($request->realizator)) {
			return [];
		}

		if (is_array($period) && count($period) === 2) { // Changed array_length to count
			$period = [
				date("Y-m-d", strtotime($period[0])),
				date("Y-m-d", strtotime($period[1])),
			];
		}

		if($request->realizator === 'all') {
			return Assortment::soldByAllDistributors($month, $year, $period);
		}
		
        $distributorId = $request->realizator['id'];

		return Assortment::soldByDistributor($distributorId, $month, $year, $period);
	}

	public function defects(Request $request)
	{
		$deflects = Assortment::defects($request->month, $request->year);

		return $deflects;
	}

	/**
	 * Возвратить авансовый отчет, чтобы редактировать предыдущий
	 */
	public function returnAvansReport(Request $request)
	{	
		 // Validate the incoming request
		 $validated = $request->validate([
			'realizator_id' => 'required', // Ensure ID is valid
		]);
	
		// Retrieve the realization record
		$realization = Realization::where('realizator', $validated['realizator_id'])
			->where('status', 4)
			->orderBy('created_at', 'desc')
			->first();
	
		// If no realization is found, return a message
		if (!$realization) {
			return response()->json([
				'message' => 'No realization found for the given realizator.',
			], 200);
		}
	
		// Update the realization record
		$realization->update([
			'status' => 3,
			'is_accepted' => 0,
			'income_id' => null
		]);
	
		// Delete associated incomes
		Income::where('realization_id', $realization->id)->delete();
	
		// Return a success message
		return response()->json([
			'message' => 'Realization status updated and associated incomes deleted successfully.',
		], 200);
	}
	


	public function naks(Request $request)
	{
	
		$data = Nak::whereMonth('created_at', $request->month)
			->whereYear('created_at', $request->year)
			->orderBy('id', 'ASC')
			->get();

		return $data;
	}

	public function getMyOrder(Request $request)
	{
		$order = User::order();
		$realization_count = Realization::where('is_produced', 0)->where('is_read', 0)->count();
		$dop_count = OrderDop::where('status', -1)->distinct('realization_id')->count();

		$myorder = null;

		if (count($order) > $request->size) {
			$myorder = $order[$request->size];
		}

		$order = [];
		$store = Store::orderBy('num', 'asc')->get();

		$latest = $request->latest;

		$ids = Realization::selectRaw('max(id) as id, realizator')
			->where('is_produced', 0)
			->groupBy('realizator')
			->pluck('id');
		
		foreach ($ids as $id) {
			$real = Realization::whereId($id)->where('is_produced', 0)->where('is_read', 0)->orderBy('id', 'ASC')->first();
			if ($real == null) continue;

			$user = User::find($real->realizator);
		
			$assort = [];

			foreach ($store as $item) {
				$assort[] = [
					'name' => $item->type,
					'order_amount' => Report::where('realization_id', $id)->where('assortment_id', $item->id)->value('order_amount'),
					'amount' => Report::where('realization_id', $id)->where('assortment_id', $item->id)->get(),
				];

			
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
			'nak' => Nak::where('finished', '0')->count(),
		];
	}

	public function getSold()
	{
		$sold = Assortment::sold();
		return Inertia::render('Sales/Sold', [
			'sold1' => $sold
		]);
	}

	public function getReport()
	{
		$realizators = User::where('position_id', '3')->get();
		return Inertia::render('Sales/Report', [
			'realizators' => $realizators
		]);
	}

	public function getOrders()
	{
		$order = User::order();
		return Inertia::render('Sales/Orders', [
			'order' => $order
		]);
	}

	public function getRealizators()
	{
		$realizators = User::where('position_id', '3')->get();
		return Inertia::render('Sales/Realizators', [
			'realizators' => $realizators
		]);
	}


	public function declineDop()
	{
		// get dop orders
		$dops = OrderDop::whereNotIn('status', [OrderDop::ACCEPTED])->get();

		// remove from report
		foreach ($dops as $dop) {
			$report = Report::where('realization_id', $dop->realization_id)->where('assortment_id', $dop->assortment)->first();

			if ($report != null) {
				$report->order_amount = $report->order_amount - $dop->order_amount;
				$report->save();
			}
		}

		foreach ($dops as $dop) {
			$dop->status = OrderDop::DECLINED;
			$dop->save();
		}

		return 'Доп. заявка отклонена';
	}

	public function readDopStatus(Request $request)
	{
		OrderDop::whereIn('id', $request->dops)->delete();
		return 'Доп. заявка удалена';
	}
	

	public function acceptDop()
	{
		$dops = OrderDop::whereNotIn('status', [OrderDop::ACCEPTED, OrderDop::DECLINED])->get();

		
		foreach ($dops as $dop) {
		
			$real = Realization::find($dop->realization_id);
			if(!$real) {
				$dop->delete();
				continue;
			}

			$real->status = 1;
			$real->is_produced = 0;
			$real->updated_at = Carbon::now();
			$real->save();

			// delete
			$dop->status = OrderDop::ACCEPTED;
			$dop->save();
		}



		return 'Доп. заявка принята';
	}


	public function deleteNak(Request $request, $id)
	{
		$nak = Nak::findOrFail($id);

		$realizationId = $nak->realization_id;

		DB::beginTransaction();

		// delete all groceries
		$groceries = Grocery::where('nak_id', $nak->id)->get();

		foreach ($groceries as $grocery) {
			// revert report's numbers
			$report = Report::where('realization_id', $nak->realization_id)->where('user_id', $nak->user_id)->where('assortment_id', $grocery->assortment_id)->first();

			if(!$report) continue;

			$report->sold -= $grocery->amount;
			$report->defect -= $grocery->brak;
			// $report->returned -= $grocery->amount;
			// $report->returned = $report->amount - $report->sold - $report->defect;
			$report->returned = $report->amount - $report->sold;
			$report->save();

			// delete grocery
			$grocery->delete();
		}

		// delete pivots
		Pivot::where('nak_id', $nak->id)->delete();

		// delete nak
		$nak->delete();

		DB::commit();

		return Nak::where('realization_id', $realizationId)->get();
	}


	public function naklad() {
		$naks = Nak::withShopAndSum(2023);
	}

	/**
	 * Экспорт таблицы авансового отчета
	 * с настройками для Exceljs
	 */
	public function exportAvansReport(Request $request) {

		$id = $request->id; // realization_id 
	
		$assortments = Store::select('type', 'id', 'price')->orderBy('num', 'asc')->get();
		$startIndex = $assortments->count() + 6;
		$data = $this->excelAvansReportTable($id, $assortments);
		$styles = $this->excelAvansReportStyles($assortments->count() + 2);
		
		return [
			'data' => $data,
			'styles' => $styles,
			'merges' => $this->excelAvansReportMerges($startIndex, $startIndex + 4, $startIndex + 13),
			'styleVariants' => $this->excelAvansReportStyleVariants(),
			'sheetName' => $data[0][0]
		];
	}
	
	/**
	 * Сформировать таблицу авансового отчета для Excel
	 */
	private function excelAvansReportTable(int $realizationId, $assortments) {
		// raw data
		$realization = Realization::with('realizator')->find($realizationId);

		$realizatorName = $realization->real ? $realization->real["first_name"] : 'Неизвестный реализатор';
		$date = $realization ? \Carbon\Carbon::parse($realization->created_at)->format('d.m.Y') : '';
		$reports = Report::where('realization_id', $realizationId)->get(); // наверное цифры с накладных
		$shops = Pivot::with('magazine')->where('realization_id', $realizationId)->get();

		// $naks = Nak::where('realization_id', $realizationId)->withShopAndSum()->get(); // ??????????

		// начало таблицы
		$data = $this->excelAvansReportHeaders($realizatorName, $date);
		$data = $this->excelAvansReportAssortments($data, $assortments, $reports, $realization->percent);
		$sums = $this->excelAvansReportSums($data, $realization->percent, $shops, $assortments->count());

		$data[] = ['возврат накл', '', '', '','','','','','↓   итог',];
		$data[] = ['перечень С.П. и Маг.', '', '10%', 'Долг-% =', $sums["totalBrakNaSummu"],'','','', $sums["itog"],];
		$data[] = ['', '', '', '','','','','','',];
		$data[] = ['', '', '', '','сумм. реализации','','','','',];
		$data[] = ['', '', '', '','',$sums["realizationSum"],'','','',];
		$data[] = ['', '', '', '','','','','','',];
		$data[] = ['', '', '', '','','','','','',];
		$data[] = ['Покупатель просит накладной и чек', '', '', '','НАЛ',$sums["nalSum"],'','','',];
		$data[] = ['', '', '', '','','','','','',];
		$data[] = ['', '', '', '','общ    %',$sums["commonSum"],'','','',];
		$data[] = ['', '', '', '','','','','','',];
		$data[] = ['', '', '', '','','','','','',];
		$data[] = ['', '', '', '','','','','','',];
		$data[] = ['', '', '', '','','','','','',];
		$data[] = ['', '', '', '','','','','','',];
		$data[] = ['', '', '', '','','','','','',];
		$data[] = ['Накладной қабылдап алдым ФИО', 'Күні', 'қолы', '','','','','','',];
		$data[] = ['', '', '', '','','','','','',];
		$data[] = ['', '', '', '','','','','','',];
		$data[] = ['', '', '', '','','','','','',];
		$data[] = ['', '', '', '','','','','','',];
		$data[] = ['', '', '', '','','','','','',];
		$data[] = ['', '', '', '','','','',' 0   ','',];
		$data[] = ['', '', '', '','','','', 0,$sums["realizatorPercent"],];

		// Суммы и магазины справа под строчкой "↓ итог"
		$startIndex = $assortments->count() + 4;

		foreach($shops as $shop) {

			if($shop->cash === 1 || $shop->is_return === 1) continue; // наличные и возвратные не пишем

			$data[$startIndex][7] = $shop->sum;
			$data[$startIndex][8] = $shop->magazine ? $shop->magazine->name : '-';
			$startIndex++;
		}

		return $data;
	}

	/**
	 * Суммы
	 */
	private function excelAvansReportSums(
		array $data,
		$realizatorPercent = 10,
		$shops, 
		$assortmentsLength
	) {
		$totalVozvratNakladnye = 0; // сумма возвратных накладных
		$realizationSum = 0; // сумма реализации

		foreach ($shops as $item) {
			if ($item->cash == 0 && $item->is_return == 0) {
				$realizationSum += $item->sum;
			}
			if($item->is_return == 1) {
				$totalVozvratNakladnye += $item->sum;
			}
		}

		// сумма с магазинов
		$totalSum = 0; 
		$totalBrakNaSummu = 0;
		for($i = 2; $i < 2 + $assortmentsLength; $i++) {
			$totalSum += (float) $data[$i][7];
			$totalBrakNaSummu += (float) $data[$i][4];
		}

		// другие суммы
		$nalSum = $totalSum - $realizationSum; // наличные сумма
		$commonSum = $nalSum * $realizatorPercent / 100; // общ %
		$itog = $totalSum + $totalVozvratNakladnye; // итого сумма по магазинам
		$kOplate = $nalSum * (1 - $realizatorPercent / 100);

		return [
			'realizatorPercent' => $realizatorPercent.'%', // процент реализатору
			'totalVozvratNakladnye' => $totalVozvratNakladnye, // сумма возвратных накладных
			'totalSum' => $totalSum, // сумма с магазинов
			'totalBrakNaSummu' => $totalBrakNaSummu, // сумма брака
			'realizationSum' => $realizationSum, // сумма реализации
			'commonSum' => $commonSum,// общ %
			'nalSum' => $nalSum, // наличные сумма
			'itog' => $itog,// итого сумма по магазинам
			'kOplate' => $kOplate,
		];
	}

	/**
	 * Получить карту стилей на ячейки
	 */
	private function excelAvansReportStyles($startIndex = 9) {
		// cell styles
		// every cell has key like 'd' or 'a'
		// which is styleVariant

		$map = [];

		$map[] = ['d', 'c', 'a', 'b', 'a', 'a', 'a', 'a', 'e'];
		$map[] = ['c', 'c', 'c', 'c', 'c', 'c', 'c', 'c', 'e'];
		
		for($i = 2; $i < $startIndex; $i++) {
			$map[] = ['c', 'a', 'a', 'a', 'a', 'b', 'b', 'b', 'e'];
		}

		$map = array_merge($map, [
			['d', 'a', 'a', 'a', 'a', 'a', 'a', 'b', 'b'], // итог
			['a', 'a', 'b', 'b', 'b', 'a', 'a', 'a', 'b'], // 1 046 571
			['b', 'b', 'a', 'a', 'a', 'a', 'a', 'b', 'b'], // грамад
			['a', 'a', 'a', 'a', 'a', 'a', 'a', 'b', 'b'], // 
			['a', 'a', 'a', 'a', 'a', 'a', 'a', 'b', 'b'], // 
			['a', 'b', 'b', 'b', 'a', 'a', 'a', 'b', 'b'], // 474 320
			['a', 'a', 'a', 'a', 'a', 'a', 'a', 'b', 'b'], // 
			['b', 'a', 'a', 'a', 'a', 'd', 'a', 'b', 'b'], // Покупатель просит накладной и чек	
			['a', 'a', 'a', 'a', 'a', 'a', 'a', 'b', 'b'], // 
			['a', 'a', 'a', 'a', 'a', 'b', 'a', 'b', 'b'], //  общ %
			['a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a'], // 
			['a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a'], // Накладной қабылдап алдым ФИО
			['a', 'a', 'a', 'a', 'a', 'a', 'a', 'b', 'b'], // 
			['a', 'a', 'a', 'a', 'a', 'a', 'a', 'b', 'b'], // 
			['b', 'b', 'b', 'a', 'a', 'a', 'a', 'b', 'b'], // 
			['b', 'b', 'b', 'a', 'a', 'a', 'a', 'b', 'b'], // 
			['b', 'b', 'b', 'a', 'a', 'a', 'a', 'b', 'b'], // 
			['b', 'b', 'b', 'a', 'a', 'a', 'a', 'b', 'b'], // 
			['a', 'a', 'a', 'a', 'a', 'a', 'a', 'b', 'b'], // 
			['a', 'a', 'a', 'a', 'a', 'a', 'a', 'b', 'b'], // 
			['a', 'a', 'a', 'a', 'a', 'a', 'a', 'b', 'b'], // 
			['a', 'a', 'a', 'a', 'a', 'a', 'a', 'b', 'b'], // 
			['b', 'b', 'b', 'a', 'a', 'a', 'a', 'd', 'a'], //  0
			['b', 'b', 'b', 'a', 'a', 'a', 'a', 'b', 'a'], //  0 10,0%
		]);

		return $map;
	}

	/**
	 *  All style variants in excel for export Avans report
	 */
	private function excelAvansReportStyleVariants() {
		$allBorder = [
			'top' => ['style' => 'thin'],
			'left' => ['style' => 'thin'],
			'bottom' => ['style' => 'thin'],
			'right' => ['style' => 'thin'],
		];
		
		$styleVariants = [
			'a' => [
				'font' => ['size' => 14],
				'border' => $allBorder,
				'alignment' => ['wrapText' => true],
			],
			'b' => [
				'font' => ['bold' => true, 'size' => 14],
				'border' => $allBorder,
				'alignment' => ['wrapText' => true],
			],
			'c' => [
				'font' => ['bold' => true, 'italic' => true, 'size' => 14],
				'border' => $allBorder,
				'alignment' => ['wrapText' => true],
			],
			'd' => [
				'font' => ['bold' => true, 'color' => ['argb' => 'FFf22c3d'], 'underline' => true, 'size' => 14],
				'border' => $allBorder,
				'alignment' => ['wrapText' => true],
			],
			'e' => [
				'font' => ['size' => 11],
				'alignment' => ['wrapText' => true],
			],
		];

		return $styleVariants;
	}

	/**
	 * Cell Merges in avans report
	 */
	private function excelAvansReportMerges(
		$rsi = 9, // ячейка cумма реализации
		$ari = 13, // ячейка Покупатель просит накладной и чек	
		$awi = 20 // ячейка Накладной қабылдап алдым ФИО
	) {
		$merges = [
			'B1:C1',
			'D1:G1',
			'E'.$rsi.':F'.$rsi,
			'A'.$ari.':B'.$ari,
			'A'.$awi.':A'.($awi + 1),
			'B'.$awi.':B'.($awi + 1),
			'C'.$awi.':C'.($awi + 1),
			'A'.($awi + 2).':C'.($awi + 3),
			'A'.($awi + 4).':C'.($awi + 5),
			'A'.($awi + 6).':C'.($awi + 7),
		];

		return $merges;
	}

	/**
	 * Получить шапку таблицы авансового отчета
	 */
	private function excelAvansReportHeaders(String $realizatorName, String $date = 'ДД.ММ.ГГГГ ЧЧ:ММ') {
		return $data = [
			[$realizatorName, 'Дата', '', $date,'','','','','',],
			['Наименование товаров', 'Отпушено', 'Возврат', 'Брак','Брак на сумму','Продано','Цена','Сумма','',],
		];
	}


	/**
	 * Получить ассортименты таблицы авансового отчета
	 */
	private function excelAvansReportAssortments(array $data, $assortments, $reports, $realizationPercent) {
		$arr = [
			// ['Ряженка', '151', '', '','','151','310','46810','',],
			// ['Кефир 900 гр.', '150', '', '3','660','148','365','54020','',],
			// ['Кефир 500 гр.', '280', '', '2','730','277','220','60940','',],
		];


		foreach ($assortments as $item) {

            $prepReport = $reports->where('assortment_id', $item->id)->first();

			$orderAmount = $prepReport ? $prepReport->order_amount : 0;
			$amount = $prepReport ? $prepReport->amount : 0;
			$defect = $prepReport ? $prepReport->defect : 0;
			$sold = $prepReport ? $prepReport->sold : 0;

			$pivotPrice = $this->_getPivotPrice($realizationPercent, $item);

			$price = $pivotPrice;
			$defectSum = $defect * $pivotPrice;
			$sum = $pivotPrice * ($sold - $defect);

			$arr[] = [
				$item->type,// 'Наименование товаров'
				$amount, // Отпушено
				$amount - $sold, // Возврат
				$defect, // Брак
				$defectSum, // Брак на сумму
				$sold - $defect, // Продано
				$price, // Цена
				$sum, // Сумма
                ''
			];
		}

		return array_merge($data, $arr);
	}
	
	/**
	 * Получить время до которго можно подавать заявки
	 */
	public function getBeforeTime(Request $request)
	{		
		$MKT = 2;
		$account = Account::find($MKT);
		return response()->json(['time' => $account ? $account->request_time : '23:59'], 200);
	}

	/**
	 * Обновить время до которго можно подавать заявки
	 */
	public function updateBeforeTime(Request $request)
	{	
		$PUSH_ADDRESS = 'http://localhost:3000/api/push';
		$MKT_ACCOUNT_ID = 2;
		$ROLE_REALIZATOR = 3;


		$account = Account::find($MKT_ACCOUNT_ID);
		$account->request_time = $request->time;
		$account->save();

		\Log::info('Время подачи заявок обновлено до ' . $request->time);

		$users = User::where('position_id', $ROLE_REALIZATOR)->whereNotNull('pushtoken')->get();

		foreach ($users as $key => $user) {


			try {
				$response = Http::post($PUSH_ADDRESS, [
					'token' => $user->pushtoken,
					'title' => 'Время заявки обновлено',
					'body' => 'Время подачи заявок обновлено до ' . $request->time
				]);

				\Log::info('Push notification SUCCESS', ['email' => $user->email]);
			} catch(\Exception $e) {
				\Log::info('Push notification ERROR', ['email' => $user->email, 'e' => $e]);
			}

		}

		return response()->json(['message' => 'Время подачи заявок обновлено до ' . $request->time], 200);
	}

}
