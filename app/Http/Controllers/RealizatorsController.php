<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Realization;
use App\Models\Realizator;
use Illuminate\Support\Facades\Redirect;
use App\Models\Order;
use App\Models\Store;
use App\Models\Nak;
use App\Models\Grocery;
use App\Models\Magazine;
use App\Models\Market;
use App\Models\Pivot;
use App\Models\Report;
use App\Models\Percent;
use App\Models\PercentStorePivot;
use DB;
use App\Models\User;
use App\Models\City;
use App\Models\OrderDop;

class RealizatorsController extends Controller
{

	// Страница реализатора
	public function index()
	{
		$realizator = Auth::user();
		$realcount = Realization::selectRaw('count(id) as amount, realizator')
			->groupBy('realizator')->with('realizator')
			->get();

		$myrealizations = Realization::where('realizator', Auth::user()->id)
			->with('realizator', 'order')
			->where('is_accepted', 0)
			->orderBy('id', 'ASC')
			->get();

		$canApply = Realization::query()
			->where('realizator', Auth::user()->id)
			->with('realizator', 'order')
			->where('is_released', 0)
			->orderBy('id', 'DESC')
			->count();

		$assortment = Store::orderBy('num', 'asc')->get();
		
		$assorder = [];
		$myassortment = [];
		$allProducts = [];

		foreach ($assortment as $item) {
			$assorder[$item->id] = 0;
			$myassortment[$item->id] = $item;
			$allProducts[] = $item;
		}
		
		/**
		 * Nak report
		 */
		$nak_ids = Nak::where('user_id', Auth::user()->id)->pluck('id');

		$groceries = Grocery::whereYear('created_at', date('Y'))
			->whereMonth('created_at', date('m'))
			->whereIn('nak_id', $nak_ids)
			->get();


		$nak_report = [];
		foreach ($assortment as $product) {
			$_groces = $groceries->where('assortment_id', $product->id);
			$nak_report[] = [
				'name' => $product->type,
				'amount' => $_groces->sum('amount'),
				'brak' => $_groces->sum('brak'),
				'sum' => $_groces->sum('sum'),
			];
		}

		// other
		$percents = Percent::orderBy('amount')->get();

		$pivotPrices = PercentStorePivot::get();

		$branches = User::find(Auth::user()->id)->branches()->orderBy('name')->get();

		$dops = OrderDop::with('assortment')
			->whereIn('status', [OrderDop::ACCEPTED, OrderDop::DECLINED])
			->whereIn('realization_id', $myrealizations->pluck('id'))
			->get();

		$data = [
			'branches' => $branches,
			'percents' => $percents,
			'pivotPrices' => $pivotPrices,
			'assortment' => $myassortment,
			'realcount' => $realcount,
			'realizator' => $realizator,
			'auth_realization' => $myrealizations,
			'assorder' => $assorder,
			'assorder1' => $assorder,
			'shops' => Magazine::where('realizator', Auth::user()->id)->get(),
			'nak_report' => $nak_report,
			'canApply' => $canApply,
			'products' => $allProducts,
			'dops' => $dops,
			'report1' => Report::where('user_id', Auth::user()->id)->whereRaw('realization_id = (select max(`realization_id`) from reports)')->with('assortment')->get()->toArray(),
		];

		return Inertia::render('Orders/Index', $data);
	}

	/**
	 *  Накладные у реализатора
	 * ПК версия
	 */
	public function nakladnyeForRealizator(Request $request) {

		$limit = 30;

		$naks = Nak::with(['grocery', 'shop'])
			->where('user_id', Auth::user()->id)
			->orderBy('created_at', 'DESC');
		

		if($request->has('page')) {
			$naks->skip(($request->page - 1) * $limit);
		}

		if($request->has('search')) {
			// where nak->shop->name like %$request->shop% in lowercase
			$naks->whereHas('shop', function($query) use ($request) {
				$query->whereRaw('lower(name) like ?', ['%' . strtolower($request->search) . '%']);
			});
		}
			
		$naks = $naks->take($limit)->get();

		foreach ($naks as $nak) {
			$nak->sum = $nak->grocery->sum('sum');
		} 

		return $naks;
	}

	public function newOrder()
	{
		$myrealizations = Realization::where('realizator', Auth::user()->id)
			->with('realizator', 'order')
			->orderBy('id', 'DESC')
			->whereDay('created_at', now())
			->get();

		$assortment = Store::orderBy('num', 'asc')->get();
		//$realizations = Realization::whereIn('id',$ids)->with('order','realizator')->get();

		$assorder = [];
		$myassortment = [];
		foreach ($assortment as $item) {
			$assorder[$item->id] = 0;
			$myassortment[$item->id] = Store::find($item->id);
		}

		$data = [
			'assortment' => $myassortment,
			'assorder1' => $assorder,
			'auth_realization' => $myrealizations,
			'percents' => Percent::orderBy('amount')->get()
		];
		// dd($data);

		return Inertia::render('Orders/New_order', $data);
	}

	public function addOrder()
	{
		// $myrealizations = Realization::where('realizator', Auth::user()->id)
		// 	->with('realizator', 'order')
		// 	->orderBy('id', 'ASC')
		// 	->whereDay('created_at', now())
		// 	->get();

		$myrealizations = Realization::where('realizator', Auth::user()->id)
			->with('realizator', 'order')
			->where('is_accepted', 0)
			->orderBy('id', 'ASC')
			// ->whereDay('created_at', now())
			->get();

		$assortment = Store::orderBy('num', 'asc')->get();
		//$realizations = Realization::whereIn('id',$ids)->with('order','realizator')->get();

		$assorder = [];
		$myassortment = [];
		foreach ($assortment as $item) {
			$assorder[$item->id] = 0;
			$myassortment[$item->id] = Store::find($item->id);
		}

		$data = [
			'assortment' => $myassortment,
			'assorder1' => $assorder,
			'auth_realization' => $myrealizations
		];
		// dd($data);

		return Inertia::render('Orders/Add_order', $data);
	}






	public function history($id)
	{
		$orders = Realization::where('realizator', $id)->with('order', 'realizator')->orderBy('id', 'desc')->get();
		$assortment = Store::orderBy('num', 'asc')->get();
		return Inertia::render('Orders/History', [
			'orders' => $orders,
			'assortment' => $assortment
		]);
	}

	public function mobHistory(Request $request)
	{
		$myrealizations = Realization::where('realizator', Auth::user()->id)
			->with('realizator', 'order')
			->orderBy('id', 'DESC')
			->whereDay('created_at', now())
			->get();

		return Inertia::render('Orders/Istoriya', [
			'auth_realization' => $myrealizations,
		]);
	}

	public function mobAvans(Request $request)
	{
		$myrealizations = Realization::where('realizator', Auth::user()->id)
			->with('realizator', 'order')
			->orderBy('id', 'ASC')
			// ->whereDay('created_at', now())
			->get();

		$realizator = Auth::user();

		$percents = Percent::orderBy('amount')->get();

		$pivotPrices = PercentStorePivot::get();

		$avansReport = Report::where('user_id', Auth::user()->id)->whereRaw('realization_id = (select max(`realization_id`) from reports)')->with('assortment')->get()->toArray();
		$avansReport = [];

		return Inertia::render('Orders/Avans', [
			'realizator' => $realizator,
			'percents' => $percents,
			'pivotPrices' => $pivotPrices,
			'report1' => $avansReport,
		]);
	}

	public function mobNakladnie(Request $request)
	{	
		$myrealizations = Realization::where('realizator', Auth::user()->id)
			->where('is_produced', 1)
			->where('is_released', 1)
			->where('is_accepted', 0)
			->with('realizator', 'order')
			->orderBy('id', 'ASC')
			// ->whereDay('created_at', now())
			->get();

		// dd([$myrealizations, Auth::user()]);

		$nak_report = [];

		$assortment = Store::orderBy('num', 'asc')->get();

		$assorder = [];
		$myassortment = [];
		$allProducts = [];

		foreach ($assortment as $item) {
			$product = Store::find($item->id);

			$assorder[$item->id] = 0;
			$myassortment[$item->id] = $product;
			$allProducts[] = $product;
		}

		$nak_ids = Nak::where('user_id', Auth::user()->id)->pluck('id');

		$groceries = Grocery::whereYear('created_at', date('Y'))
			->whereMonth('created_at', date('m'))
			->whereIn('nak_id', $nak_ids)
			->get();

		foreach ($assortment as $product) {
			// $nak_report[] = [
			// 	'name' => $product->type,
			// 	'amount' => Grocery::whereYear('created_at', date('Y'))->whereMonth('created_at', date('m'))->whereIn('nak_id', $nak_ids)->where('assortment_id', $product->id)->sum('amount'),
			// 	'brak' => Grocery::whereYear('created_at', date('Y'))->whereMonth('created_at', date('m'))->whereIn('nak_id',$nak_ids)->where('assortment_id', $product->id)->sum('brak'),
			// 	'sum' => Grocery::whereYear('created_at', date('Y'))->whereMonth('created_at', date('m'))->whereIn('nak_id',$nak_ids)->where('assortment_id', $product->id)->sum('sum'),
			// ];	

			$_groceries = $groceries->where('assortment_id', $product->id);
			$nak_report[] = [
				'name' => $product->type,
				'amount' => $_groceries->sum('amount'),
				'brak' => $_groceries->sum('brak'),
				'sum' => $_groceries->sum('sum'),
			];
		}

		$percents = Percent::orderBy('amount')->get();
		$pivotPrices = PercentStorePivot::get();

		// dd($pivotPrices->toArray());

		// dd([$myrealizations->toArray(), $myassortment, $nak_report]);

		return Inertia::render('Orders/Naks', [
			'auth_realization' => $myrealizations,
			'nakladnoe' => [], //Nak::with(['grocery', 'shop'])->where('user_id', Auth::user()->id)->orderBy('id', 'DESC')->get(),
			'branches' => User::find(Auth::user()->id)->branches()->orderBy('name')->get(),
			'nak_report' => $nak_report,
			'assortment' => $myassortment,
			'percents' => $percents,
			'pivotPrices' => $pivotPrices,
			'products' => $allProducts,
		]);
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

	public function saveNak(Request $request)
	{
		DB::beginTransaction();

		$realization = Realization::find($request->realization_id);

		$branch = null;

		// branch is chosen or created a new one?
		if ($request->branch_id) {
			$branch = Branch::where('id', $request->branch_id)->firstOrFail();
		} 
		// new branch
		else {
			$newName = trim($request->new_branch);

			$branch = Branch::where('name', $newName)->first();

			if (!$branch) {
				$market = Market::create([
					'name' => $newName,
        			'debt_start' => 0,
				]);

				$branch = Branch::create([
					'name' => $newName,
					'city_id' => City::first()->id,	// what if now city?
					'market_id' => $market->id,
					'initial_debt' => 0,
					'paid' => 0,
					'debt' => 0,
					'sold' => 0,
				]);
			}

			DB::table('pivot_branch_realizator')->insert([
				'branch_id' => $branch->id,
				'user_id' => Auth::user()->id,
			]);
		} 

		// создать запись накладной
		$nak = new Nak();
		$nak->user_id = Auth::user()->id;
		$nak->shop_id = $branch->id;
		$nak->consegnation = $request->option;
		$nak->realization_id = $request->realization_id;
		$nak->is_return = $request->option == 9 ? 1 : 0;
		$nak->save();

		$mysum = 0;

		foreach ($request->items as $key => $value) {
			// сохранить данные накладной
			$grocery = new Grocery();
			$grocery->nak_id = $nak->id;
			$grocery->assortment_id = Store::where('type', 'LIKE', $value)->value('id');
			$grocery->amount = $request->amounts[$key];
			$grocery->brak = $request->brak[$key];
			$grocery->price = $this->_getPivotPrice(intval($realization->percent), Store::where('id', $grocery->assortment_id)->first());
			$grocery->sum = $grocery->price * ($grocery->amount - $grocery->brak);
			$grocery->save();

			$mysum = $mysum + $grocery->sum;

			// Report
			$report = Report::where('realization_id', $request->realization_id)
				->where('user_id', $request->user()->id)
				->where('assortment_id', $grocery->assortment_id)
				->first();
				
			if ($report) {
		
				$report->sold += $grocery->amount;
				$report->defect += $grocery->brak;
				//$report->returned = $report->amount - $report->sold - $report->defect;
				$report->returned = $report->amount - $report->sold;
				$report->save();

				if ($report->returned + $report->sold > $report->amount && $report->order_amount != 0) {
					$grocery->correct = 1;
					$grocery->save();
				}
			}			
		}

		// pivot shop + realization
		$pivot = new Pivot();
		$pivot->realization_id = $request->realization_id;
		$pivot->magazine_id = $nak->shop_id;
		$pivot->sum = $mysum;
		$pivot->cash = in_array($request->option, [1, 9]) ? 0 : 1;
		$pivot->is_return = $request->option == 9 ? 1 : 0;
		$pivot->nak_id = $nak->id;
		$pivot->save();

		// update sold in branch
		if ($request->option == 9) {
			$branch->paid += abs($mysum);
		} else {
			$branch->sold += $mysum;
		}
		$branch->save();

		DB::commit();


		return [
			'message' => 'Накладная сохранена'
		];
	}

	public function nakladnaya($id)
	{
		$nak = Nak::with(['grocery', 'shop', 'user'])->whereId($id)->firstOrFail();
		$grocery = Grocery::where('nak_id', $nak->id)->get();
	
		if ($nak->consegnation == 1) {
			$consegnation = "Консегнация для МКТ";
		} else if ($nak->consegnation == 2) {
			$consegnation =	"Консегнация для себя";
		} else if ($nak->consegnation == 9) {
			$consegnation = 'Возврат';
		} else {
			$consegnation = 'Оплата наличными';
		}

		$headers = [
			'#',
			'Атауы/Наименование',
			'Кол-во',
			'Брак',
			'Цена',
			'Сумма',
		];

		$table = [];

		$totalSum = 0;

		foreach ($grocery as $key => $item) {
			$p = Store::where('id', $item['assortment_id'])->first();

			$table[] = [
				$key + 1,
				$p ? $p->type : '-',
				$item['amount'],
				$item['brak'],
				$item['price'],
				$item['sum'],
				$p ? $p->id : '-',
			];

			$totalSum += $item['sum'];
		}

		$user_id = $nak->user_id;

		return [
			'branches' => Branch::select('id', 'name', 'market_id')
					// ->whereHas('realizators', function ($query) use ($user_id) {
					// 	$query->where('user_id', $user_id);
					// }) // ограничение по своим магазинам
					->orderBy('name')
					->get(),
			'type' => $nak->consegnation, // добавил чтобы не ломать consegnation
			'market' => $nak->shop, // добавил чтобы не ломать shop
			'headers' => $headers,
			'table' => $table,
			'consegnation' => $consegnation,
			'realizator' => $nak->user->last_name . ' ' . $nak->user->first_name,
			'shop' => $nak->shop->name,
			'sum' => $totalSum,
			'date' => $nak->created_at ? $nak->created_at->format('d.m.Y H:i') : '-',
			'user_id' => $nak->user_id,
		];
	}


	/**
	 * Создать магазин
	 */
	public function createMarket(Request $request) {
		$name = $request->name;
		$user_id = $request->user_id;

		$market = Market::create([
			'name' => $name,
			'debt_start' => 0,
		]);

		$branch = Branch::create([
			'name' => $name,
			'city_id' => City::first()->id ?? 0,	// what if now city?
			'market_id' => $market->id,
			'initial_debt' => 0,
			'paid' => 0,
			'debt' => 0,
			'sold' => 0,
		]);

		DB::table('pivot_branch_realizator')->insert([
			'branch_id' => $branch->id,
			'user_id' => $user_id,
		]);

		return $branch;
	}

	public function nakladnayaUpdate(Request $request) {

		DB::beginTransaction();

		$id = $request->id;
		$items = $request->items;
		$nakladnayaSum = 0;

		$nak = Nak::with(['grocery', 'shop', 'user'])->whereId($id)->firstOrFail();
		$groceries = Grocery::where('nak_id', $nak->id)->get();

		if(auth()->user()->position_id !== 1) { // не директор
			if($nak->created_at->diffInDays(now()) > 1) {
				return response()->json([
					'message' => 'Нельзя редактировать накладную старше 1 дня'
				], 500);
			}
		}

		// update nak
		$new_shop_id = $request->shop_id;
		$new_consegnation = $request->type;
		$old_shop_id = $nak->shop_id;
		$old_consegnation = $nak->consegnation;

		$nak->is_return = $new_consegnation == 9 ? 1 : 0;
		$nak->shop_id = $new_shop_id;
		$nak->consegnation = $request->type;
		$nak->save();

		// 1. Update grocery and report
		foreach ($items as $item) {


			// 1.1 update grocery
			$grocery = $groceries->where('assortment_id', $item['store_id'])->first();
			if(!$grocery) continue;

			$old_amount = $grocery->amount;
			$old_brak = $grocery->brak;

			$grocery->amount = $item['amount'];
			$grocery->brak = $item['brak'];
			$grocery->sum = ($item['amount'] - $item['brak']) * $item['price'];
			$grocery->save();

			$nakladnayaSum += $grocery->sum;



			// 1.2 update report
			$report = Report::where('realization_id', $nak->realization_id)
				//->where('user_id', $request->user()->id)
				->where('assortment_id', $grocery->assortment_id)
				->first();
				
			if ($report) {
		
				$report->sold += $grocery->amount - $old_amount;
				$report->defect += $grocery->brak - $old_brak;
				$report->returned = $report->amount - $report->sold;
				$report->save();

				if ($report->returned + $report->sold > $report->amount && $report->order_amount != 0) {
					$grocery->correct = 1;
					$grocery->save();
				}
			}			
		}

		// 2. pivot shop + realization
		$pivot = Pivot::where('realization_id', $nak->realization_id)
			->where('magazine_id', $old_shop_id)
			->where('nak_id', $nak->id)
			->first();
		
		$newpivot = Pivot::where('realization_id', $nak->realization_id)
			->where('magazine_id', $new_shop_id)
			->where('nak_id', $nak->id)
			->first();
		

		$oldSum = 0;
		if($pivot) {
			$oldSum = $pivot->sum;
			$pivot->sum = $nakladnayaSum;
			$pivot->cash = in_array($old_consegnation, [1, 9]) ? 0 : 1;
			$pivot->is_return = $old_consegnation == 9 ? 1 : 0;
			$pivot->save();
		}

		if($newpivot) {
			$newpivot->sum = $nakladnayaSum;
			$pivot->cash = in_array($new_consegnation, [1, 9]) ? 0 : 1;
			$pivot->is_return = $new_consegnation == 9 ? 1 : 0;
			$newpivot->save();
		} else {
			$pivot = new Pivot();
			$pivot->realization_id = $nak->realization_id;
			$pivot->magazine_id = $new_shop_id;
			$pivot->sum = $nakladnayaSum;
			$pivot->cash = in_array($new_consegnation, [1, 9]) ? 0 : 1;
			$pivot->is_return = $new_consegnation == 9 ? 1 : 0;
			$pivot->nak_id = $nak->id;
			$pivot->save();
		}
	

		// 3. update sold in branch
		$old_branch = Branch::where('id', $old_shop_id)->first();
		$new_branch = Branch::where('id', $new_shop_id)->first();

		// если поменяли магазин
		
		if($old_shop_id != $new_shop_id) {
		
			if($old_branch) {
				if ($old_consegnation == 9) { // возврат
					$old_branch->paid -= $oldSum;
				} else {
					$old_branch->sold -= $oldSum;
				}
				$old_branch->save();
			}

			if($new_branch) {
				if ($new_consegnation == 9) {// возврат
					$new_branch->paid += $nakladnayaSum;
				} else {
					$new_branch->sold += $nakladnayaSum;
				}
				$new_branch->save();
			}
		} 
		
		// если не поменяли магазин
		if($old_shop_id == $new_shop_id && $new_branch != null) {
			if ($new_consegnation == 9) {// возврат
				$new_branch->paid += abs($nakladnayaSum - $oldSum);
			} else {
				$new_branch->sold += $nakladnayaSum - $oldSum;
			}

			$new_branch->save();
		}

		DB::commit();
	}	
	

	public function blank($id)
	{
		$nak = Nak::with(['grocery', 'shop', 'user'])->whereId($id)->firstOrFail();
		$grocery = Grocery::where('nak_id', $nak->id)->get();
		// dd($nak);

		if ($nak->consegnation == 1) {
			$consegnation = "Консегнация для МКТ";
		} else if ($nak->consegnation == 2) {
			$consegnation =	"Консегнация для себя";
		} else if ($nak->consegnation == 9) {
			$consegnation = 'Возврат';
		} else {
			$consegnation = 'Оплата наличными';
		}

		$phpWord = new \PhpOffice\PhpWord\PhpWord();

		$paper = new \PhpOffice\PhpWord\Style\Paper();
		$paper->setSize('A4');

		$section = $phpWord->addSection(array(
			'pageSizeW' => $paper->getWidth(),
			'pageSizeH' => $paper->getHeight(),
			'orientation' => 'portrait',
		));

		$imageStyle = array(
			'positioning' => 'absolute',
			'posHorizontalRel' => 'margin',
			'posVerticalRel' => 'line',
		);

		$section->addImage("img/logo4.png", $imageStyle);

		$section->addText('				СПК Майлыкент-Сут                                ' . $nak->shop->name);

		$section->addText('				' . date('d/m/Y', strtotime($nak->created_at)) . '       		                            ' . $consegnation);

		$section->addText('Реализатор: ' . $nak->user->last_name . ' ' . $nak->user->first_name);

		$styleCell = array('borderTopSize' => 1, 'borderTopColor' => 'black', 'borderLeftSize' => 1, 'borderLeftColor' => 'black', 'borderRightSize' => 1, 'borderRightColor' => 'black', 'borderBottomSize' => 1, 'borderBottomColor' => 'black');
		$fontStyle = array('italic' => true, 'size' => 10, 'name' => 'Times New Roman', 'afterSpacing' => 0, 'Spacing' => 0, 'cellMargin' => 0);

		$TfontStyle = array('bold' => true, 'italic' => false, 'size' => 10, 'name' => 'Times New Roman', 'afterSpacing' => 0, 'Spacing' => 0, 'cellMargin' => 0);
		$cfontStyle = array('allCaps' => true, 'italic' => false, 'size' => 10, 'name' => 'Times New Roman', 'afterSpacing' => 0, 'Spacing' => 0, 'cellMargin' => 0);

		$table = $section->addTable('myOwnTableStyle', array(
			'borderSize' => 1,
			'borderColor' => '999999',
			'afterSpacing' => 0,
			'spacing' => 0,
			'cellMargin' => 0,
			'layout' => \PhpOffice\PhpWord\Style\Table::LAYOUT_FIXED,
			// 'unit' => \PhpOffice\PhpWord\Style\Table::WIDTH_PERCENT, 'width' => 100 * 50,
		));

		$unit10 = ['unit' => \PhpOffice\PhpWord\Style\Table::WIDTH_PERCENT, 'width' => 10 * 50,];
		$unit15 = ['unit' => \PhpOffice\PhpWord\Style\Table::WIDTH_PERCENT, 'width' => 15 * 50,];
		$unit30 = ['unit' => \PhpOffice\PhpWord\Style\Table::WIDTH_PERCENT, 'width' => 30 * 50,];

		$table->addRow();
		$table->addCell(300, $styleCell, ['unit' => $unit10])->addText('#', $TfontStyle);
		$table->addCell(4000, $styleCell, ['unit' => $unit30])->addText("Атауы/Наименование", $cfontStyle);
		$table->addCell(1000, $styleCell, ['unit' => $unit15])->addText('Кол-во', $TfontStyle);
		$table->addCell(1000, $styleCell, ['unit' => $unit15])->addText("Брак", $fontStyle);
		$table->addCell(1000, $styleCell, ['unit' => $unit15])->addText("Цена", $fontStyle);
		$table->addCell(1000, $styleCell, ['unit' => $unit15])->addText("Сумма", $fontStyle);


		$totalSum = 0;

		foreach ($grocery as $key => $item) {
			$p = Store::where('id', $item['assortment_id'])->value('type');
			$table->addRow();
			$table->addCell(null, $styleCell)->addText($key + 1, $TfontStyle);
			$table->addCell(null, $styleCell)->addText($p, $cfontStyle);
			$table->addCell(null, $styleCell)->addText($item['amount'], $TfontStyle);
			$table->addCell(null, $styleCell)->addText($item['brak'], $fontStyle);
			$table->addCell(null, $styleCell)->addText($item['price'], $fontStyle);
			$table->addCell(null, $styleCell)->addText($item['sum'], $fontStyle);

			$totalSum += $item['sum'];
		}

		for ($i = count($grocery); $i < 21; $i++) {

			$table->addRow();
			$table->addCell(null, $styleCell)->addText($i + 1, $TfontStyle);
			$table->addCell(null, $styleCell)->addText("", $cfontStyle);
			$table->addCell(null, $styleCell)->addText('', $TfontStyle);
			$table->addCell(null, $styleCell)->addText("", $fontStyle);
			$table->addCell(null, $styleCell)->addText("", $fontStyle);
			$table->addCell(null, $styleCell)->addText("", $fontStyle);
		}


		$table->addRow();
		$table->addCell(null, $styleCell)->addText("", $TfontStyle);
		$table->addCell(null, $styleCell)->addText("", $cfontStyle);
		$table->addCell(null, $styleCell)->addText("", $TfontStyle);
		$table->addCell(null, $styleCell)->addText("", $fontStyle);
		$table->addCell(null, $styleCell)->addText("ИТОГ", $fontStyle);
		$table->addCell(null, $styleCell)->addText($totalSum, $fontStyle);

		// $section->addText('ИТОГ: ' . $totalSum . ' тг');

		$section->addText('');
		$section->addText('');
		$section->addText('_________________				                                ___________________');

		$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');

		$dateStr = ' для ' . $nak->shop->name;
		$filename = 'Накладная' . $dateStr . '.docx';

		try {
			$objWriter->save(storage_path($filename));
		} catch (\Exception $e) {
		}

		return response()->download(storage_path($filename));
		// return response()->download(storage_path('mkt-nak.docx'));
	}

	public function nakStatus()
	{
		Nak::where('finished', '0')->update(['finished' => '1']);
	}

	public function getNakReportByMonth(Request $request)
	{

		$nak_report = [];
		$products = Store::orderBy('num', 'asc')->get();
		foreach ($products as $product) {
			$nak_report[] = [
				'name' => $product->type,
				'amount' => Grocery::whereYear('created_at', date('Y'))->whereMonth('created_at', $request->month)->whereIn('nak_id', Nak::where('user_id', Auth::user()->id)->pluck('id'))->where('assortment_id', $product->id)->sum('amount'),
				'brak' => Grocery::whereYear('created_at', date('Y'))->whereMonth('created_at', $request->month)->whereIn('nak_id', Nak::where('user_id', Auth::user()->id)->pluck('id'))->where('assortment_id', $product->id)->sum('brak'),
				'sum' => Grocery::whereYear('created_at', date('Y'))->whereMonth('created_at', $request->month)->whereIn('nak_id', Nak::where('user_id', Auth::user()->id)->pluck('id'))->where('assortment_id', $product->id)->sum('sum'),
			];
		}

		return $nak_report;
	}
}
