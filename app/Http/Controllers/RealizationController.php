<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
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
use Carbon\Carbon;
use App\Models\Month;
use App\Models\NakReturn;
use App\Models\Percent;
use App\Models\Oweshop;
use App\Models\PercentStorePivot;
use App\Models\Branch;
use App\Models\Weightstore;
use App\Services\RealizationService;
use App\Services\WeightstoreService;
use DB;

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
            'count' => $realizationService->quantityOfDistributorsRealizations(),
			'monthes' => Month::getShortMonthsArray(),
			'pivotPrices' => PercentStorePivot::get(),
			'oweshops' => Oweshop::orderBy('shop')->get(),
            'sold1' => Assortment::soldAll($month->month, $month->year), // 0.11 s
            'realization_count' => Realization::notRead()->notProduced()->count(),
            //'realizators' => User::isDistributor()->with('realization', 'magazine')->orderBy('id', 'ASC')->get(),
            'realizators' => User::isDistributor()->orderBy('id', 'ASC')->get(),
			'realizations' => Realization::whereIn('id', $ids)->with('order', 'realizator')->get(),
			'realizators_total' => [
				'total_sum' => Realization::where('status', '2')->sum('realization_sum'),
				'total_defect' => Realization::where('status', '2')->sum('defect_sum'),
				'average_percent' => Realization::where('status', '2')->avg('percent'),
				'income' => Realization::where('status', '2')->sum('income'),
			],
            //'reports' => Report::whereBetween('created_at', $betweenDate)->get(), // 0.18 s
			'report1' => Report::where('user_id', auth()->id()) // 0.2 s
                ->whereRaw('realization_id = (select max(`realization_id`) from reports)')
                ->with('assortment')
                ->get()
                ->toArray(),
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


	public function getItogData(Request $request)
	{
		$output = [];
		$monthOutput = [];

		$now = Carbon::now();

		$month = $request->month;
		$year = $request->year ? $request->year : $now->year;


		// $beginDate = Carbon::createFromDate($now->year, $month, 1);

		$realizations = Realization::query()
			->with(['reports'])
			->whereYear('created_at', '=', $year)
			->whereMonth('created_at', '=', $month)
			->get();

		// dd([$now, $now->year, $month, $realizations->toArray()]);

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

		return ['data' => $output, 'total' => $monthOutput];
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



            // $id = Realization::where('realizator', $request->id)
            //     ->where('is_accepted', 0)
            //     ->orderBy('id', 'ASC')
            //     ->pluck('id')
            //     ->first();

                //$real = Realization::where('id', $id)->first();
               // $realization = Report::where('realization_id', $id)->with('assortment')->get();
               // $realization = $realization->sortBy(fn($item, $key) => $item->assortment->num);




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

        //dd($myreport);
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

	public function sendOrder(Request $request)
	{
		// dd($request->all());

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

		return ['realization' => Realization::where('id', $realization->id)->with('order', 'realizator')->get(), 'message' => 'Заявка отправлена на обработку'];
	}

	public function updateOrder(Request $request)
	{	
		foreach ($request->order as $key => $value) {
			// if ($value != 0) {
			$dop = OrderDop::where('realization_id', $request->realization_id)->where('assortment', $key)->first();

			if($dop->status !== -1) {
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

	public function getRealizatorOrder(Request $request)
	{
		$id = Realization::where('realizator', $request->id)
			->where('is_accepted', 0)
			->orderBy('id', 'ASC')
			->pluck('id')
			->first();

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
		// dd($real->toArray());
		$percent = Percent::where('amount', intval($real->percent))->first();

		$cash = Realization::where('id', $id)->pluck('cash');
		$majit = Realization::where('id', $id)->pluck('majit');
		$sordor = Realization::where('id', $id)->pluck('sordor');

		$realization = Report::where('realization_id', $id)->with('assortment')->get();
		$realization = $realization->sortBy(fn($item, $key) => $item->assortment->num);

		$realizationNaks = Nak::where('realization_id', $id)->with(['shop'])->get();

		//dd($id);
		$magazines = Pivot::where('realization_id', $id)->get();
		$columns = [];

		foreach ($magazines as $item) {
			// dd($item);

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

		// dd([$magazines, $columns]);

		if (count($columns) <= 0) {
			$columns[] = ['magazine' => null, 'amount' => null, 'pivot' => null, 'isNal' => false, 'nak' => null,];
		}

		$nakReturns = NakReturn::query()
			->with('oweshop')
			->where('realization_id', $real->id)
			->get();

		// dd($columns);

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
			'magazine' => User::find($real->realizator)->branches()->orderBy('name')->get(),
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

		/****************************
		 * 2. Цикл продуктов
		 */
		foreach ($request->order as $key => $item) {

			if($item['order_amount'] <= 0) continue;
			
			$record = $item['amount'][0];

			/****************************
			 * 2.1 Сохраняем отчет
			 */
			$order = Report::find($record['id']);
			$order->amount = $record['amount'];
			$order->save();

			
			/****************************
			 * 2.2 Берем тары для продукции
			 */
			$store = Store::find($record['assortment_id']);

			$pivots = DB::table('tara_store')->where('store_id', $store->id)->get();

			foreach ($pivots as $pivot) {
				$tara = Tara::find($pivot->tara_id);
				if (!$tara) continue;

				$tara->amount = $tara->amount - $record['amount'] * $pivot->need;
				$tara->save();
			}

			/****************************
			 * 2.3 Создаем продукт из весового склада
			 */
			(new WeightstoreService)->createProduct($record['assortment_id'], $record['amount']);


			/****************************
			 * 2.4 Перемещаем продукт в склад
			 */
			$store->amount += $record['amount'];
			$store->save();
			
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
		// dd($request->all());
		$realization = Realization::find($request->realization_id);

		if ($realization->is_produced == 0) {
			return ['status' => 'error', 'message' => 'Ошибка: продукция еще не изготовлена'];
		}

		$reports = $request->report;

		foreach ($reports as $key => $report) {
			$product = Report::find($report['id']);

			$store = Store::find($product->assortment_id);
			$store->amount = $store->amount - $report['amount'] + $product->amount; // return old values
			// dd($store->amount, $report['amount'], $product['amount']);
			$store->save();

			$product->amount = $report['amount'];
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
			$store->amount = $store->amount - $report['amount'] + $product->amount;
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
		// //dd($request->realization_id);
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
		// dd($real->toArray());
		$percent = Percent::where('amount', intval($real->percent))->first();

		$cash = Realization::where('id', $id)->pluck('cash');
		$majit = Realization::where('id', $id)->pluck('majit');
		$sordor = Realization::where('id', $id)->pluck('sordor');
		$realization = Report::where('realization_id', $id)->with('assortment')->get();

		$realizationNaks = Nak::where('realization_id', $id)->with(['shop'])->get();

		//dd($id);
		$magazines = Pivot::where('realization_id', $id)->get();
		$columns = [];

		foreach ($magazines as $item) {
			// dd($item);

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

		// dd([$magazines, $columns]);

		if (count($columns) <= 0) {
			$columns[] = ['magazine' => null, 'amount' => null, 'pivot' => null, 'isNal' => false, 'nak' => null,];
		}

		$nakReturns = NakReturn::query()
			->with('oweshop')
			->where('realization_id', $real->id)
			->get();

		// dd($columns);

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

	public function sold1(Request $request)
	{
        if(empty($request->realizator)) {
            return [];
        }

        $distributorId = $request->realizator['id'];

		return Assortment::soldByDistributor($distributorId, $request->month, $request->year);
	}

	public function defects(Request $request)
	{
		$deflects = Assortment::defects($request->month, $request->year);

		return $deflects;
	}

	public function naks(Request $request)
	{
		// dd($request->all());

		// return ['ok', $request->month, $request->year];

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
		//dd($request->all());

		foreach ($ids as $id) {
			$real = Realization::whereId($id)->where('is_produced', 0)->where('is_read', 0)->orderBy('id', 'ASC')->first();
			if ($real == null) continue;

			$user = User::find($real->realizator);
			// dd($realization);
			$assort = [];

			foreach ($store as $item) {
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
		$dops = OrderDop::get();

		foreach ($dops as $dop) {
			$real = Realization::find($dop->realization_id);
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
			$report->sold -= $grocery->amount;
			$report->defect -= $grocery->brak;
			// $report->returned -= $grocery->amount;
			$report->returned = $report->amount - $report->sold - $report->defect;
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
}
