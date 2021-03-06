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
use App\Models\Pivot;
use App\Models\Report;
use App\Models\Percent;
use App\Models\PercentStorePivot;
use DB;
use App\Models\User;

/**
 * 
 */
class RealizatorsController extends Controller
{

	public function index()
	{
		//$ids = Realization::selectRaw('max(id) as id, realizator')->where('realizator', Auth::user()->id)->groupBy('realizator')->pluck('id');
		$realizator = Auth::user();
		$realcount = Realization::selectRaw('count(id) as amount, realizator')->groupBy('realizator')->with('realizator')->get();

		$myrealizations = Realization::where('realizator', Auth::user()->id)
			->with('realizator', 'order')
			->where('is_accepted', 0)
			->orderBy('id', 'ASC')
			// ->whereDay('created_at', now())
			->get();

		$canApply = Realization::query()
			->where('realizator', Auth::user()->id)
			->with('realizator', 'order')
			->where('is_released', 0)
			->orderBy('id', 'DESC')
			// ->whereDay('created_at', now())
			->count();

		// dd($myrealizations->toArray());

		$assortment = Store::orderBy('num', 'asc')->get();
		//$realizations = Realization::whereIn('id',$ids)->with('order','realizator')->get();

		$assorder = [];
		$myassortment = [];
		$allProducts = [];

		foreach ($assortment as $item) {
			$product = Store::find($item->id);

			$assorder[$item->id] = 0;
			$myassortment[$item->id] = $product;
			$allProducts[] = $product;
		}

		$nak_report = [];
		$products = Store::orderBy('num', 'asc')->get();
		foreach ($products as $product) {
			$nak_report[] = [
				'name' => $product->type,
				'amount' => Grocery::whereYear('created_at', date('Y'))->whereMonth('created_at', date('m'))->whereIn('nak_id', Nak::where('user_id', Auth::user()->id)->pluck('id'))->where('assortment_id', $product->id)->sum('amount'),
				'brak' => Grocery::whereYear('created_at', date('Y'))->whereMonth('created_at', date('m'))->whereIn('nak_id', Nak::where('user_id', Auth::user()->id)->pluck('id'))->where('assortment_id', $product->id)->sum('brak'),
				'sum' => Grocery::whereYear('created_at', date('Y'))->whereMonth('created_at', date('m'))->whereIn('nak_id', Nak::where('user_id', Auth::user()->id)->pluck('id'))->where('assortment_id', $product->id)->sum('sum'),
			];
		}

		$percents = Percent::orderBy('amount')->get();

		$pivotPrices = PercentStorePivot::get();

		// dd($myassortment);

		$branches = User::find(Auth::user()->id)->branches()->orderBy('name')->get();

		$data = [
			//'realizations' => $realizations,
			'branches' => $branches,
			'percents' => $percents,
			'pivotPrices' => $pivotPrices,
			'assortment' => $myassortment,
			'realcount' => $realcount,
			'realizator' => $realizator,
			'auth_realization' => $myrealizations,
			'assorder' => $assorder,
			'assorder1' => $assorder,
			'nakladnoe' => Nak::with(['grocery', 'shop'])->where('user_id', Auth::user()->id)->orderBy('created_at', 'DESC')->get(),
			'shops' => Magazine::where('realizator', Auth::user()->id)->get(),
			'nak_report' => $nak_report,
			'canApply' => $canApply,
			'products' => $allProducts,
			'report1' => Report::where('user_id', Auth::user()->id)->whereRaw('realization_id = (select max(`realization_id`) from reports)')->with('assortment')->get()->toArray(),
		];
		// dd($data);

		return Inertia::render('Orders/Index', $data);
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
		$myrealizations = Realization::where('realizator', Auth::user()->id)
			->with('realizator', 'order')
			->orderBy('id', 'ASC')
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

		foreach ($assortment as $product) {
			$nak_report[] = [
				'name' => $product->type,
				'amount' => Grocery::whereYear('created_at', date('Y'))->whereMonth('created_at', date('m'))->whereIn('nak_id', Nak::where('user_id', Auth::user()->id)->pluck('id'))->where('assortment_id', $product->id)->sum('amount'),
				'brak' => Grocery::whereYear('created_at', date('Y'))->whereMonth('created_at', date('m'))->whereIn('nak_id', Nak::where('user_id', Auth::user()->id)->pluck('id'))->where('assortment_id', $product->id)->sum('brak'),
				'sum' => Grocery::whereYear('created_at', date('Y'))->whereMonth('created_at', date('m'))->whereIn('nak_id', Nak::where('user_id', Auth::user()->id)->pluck('id'))->where('assortment_id', $product->id)->sum('sum'),
			];
		}

		$percents = Percent::orderBy('amount')->get();
		$pivotPrices = PercentStorePivot::get();

		// dd($pivotPrices->toArray());

		// dd([$myrealizations->toArray(), $myassortment, $nak_report]);

		return Inertia::render('Orders/Naks', [
			'auth_realization' => $myrealizations,
			'nakladnoe' => Nak::with(['grocery', 'shop'])->where('user_id', Auth::user()->id)->orderBy('id', 'DESC')->get(),
			'branches' => User::find(Auth::user()->id)->branches()->orderBy('name')->get(),
			'nak_report' => $nak_report,
			'assortment' => $myassortment,
			'percents' => $percents,
			'pivotPrices' => $pivotPrices,
			'products' => $allProducts,
		]);
	}

	public function saveNak(Request $request)
	{
		// dd($request->all());

		DB::beginTransaction();

		$realization = Realization::find($request->realization_id);
		$branch = Branch::where('id', $request->branch_id)->firstOrFail();

		// if ($branch == null) {
		// 	$magazine = new Magazine();
		// 	$magazine->name = $request->shop;
		// 	$magazine->realizator = Auth::user()->id;
		// 	$magazine->timestamps = false;
		// 	$magazine->save();
		// }

		// ?????????????? ???????????? ??????????????????
		$nak = new Nak();
		$nak->user_id = Auth::user()->id;
		$nak->shop_id = $branch->id;
		$nak->consegnation = $request->option;
		$nak->realization_id = $request->realization_id;
		$nak->is_return = $request->option == 9 ? 1 : 0;
		$nak->save();

		$mysum = 0;

		foreach ($request->items as $key => $value) {
			// ?????????????????? ???????????? ??????????????????
			$grocery = new Grocery();
			$grocery->nak_id = $nak->id;
			$grocery->assortment_id = Store::where('type', 'LIKE', $value)->value('id');
			$grocery->amount = $request->amounts[$key];
			$grocery->brak = $request->brak[$key];
			$grocery->price = $this->_getPivotPrice(intval($realization->percent), Store::where('id', $grocery->assortment_id)->first());
			$grocery->sum = $grocery->price * ($grocery->amount - $grocery->brak);
			$grocery->save();

			$mysum = $mysum + $grocery->sum;

			// ???????? ???????????????????? ??????????????????, ???? ???? ???????????? ???? ???????? ????????????
			if ($request->option != 9) {
				$report = Report::where('realization_id', $request->realization_id)->where('user_id', Auth::user()->id)->where('assortment_id', $grocery->assortment_id)->first();
				$report->sold += $grocery->amount;
				$report->defect += $grocery->brak;
				// $report->returned -= $grocery->amount;
				$report->returned = $report->amount - $report->sold - $report->defect;
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
		$pivot->cash = in_array($request->option, [1, 2, 9]) ? 0 : 1;
		$pivot->is_return = $request->option == 9 ? 1 : 0;
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
			'message' => '?????????????????? ??????????????????'
		];
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

	public function blank($id)
	{
		$nak = Nak::with(['grocery', 'shop', 'user'])->whereId($id)->firstOrFail();
		$grocery = Grocery::where('nak_id', $nak->id)->get();
		// dd($nak);

		if ($nak->consegnation == 1) {
			$consegnation = "?????????????????????? ?????? ??????";
		} else if ($nak->consegnation == 2) {
			$consegnation =	"?????????????????????? ?????? ????????";
		} else if ($nak->consegnation == 9) {
			$consegnation = '??????????????';
		} else {
			$consegnation = '???????????? ??????????????????';
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

		$section->addText('				?????? ??????????????????-??????                                ' . $nak->shop->name);

		$section->addText('				' . date('d/m/Y', strtotime($nak->created_at)) . '       		                            ' . $consegnation);

		$section->addText('????????????????????: ' . $nak->user->last_name . ' ' . $nak->user->first_name);

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
		$table->addCell(4000, $styleCell, ['unit' => $unit30])->addText("??????????/????????????????????????", $cfontStyle);
		$table->addCell(1000, $styleCell, ['unit' => $unit15])->addText('??????-????', $TfontStyle);
		$table->addCell(1000, $styleCell, ['unit' => $unit15])->addText("????????", $fontStyle);
		$table->addCell(1000, $styleCell, ['unit' => $unit15])->addText("????????", $fontStyle);
		$table->addCell(1000, $styleCell, ['unit' => $unit15])->addText("??????????", $fontStyle);


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
		$table->addCell(null, $styleCell)->addText("????????", $fontStyle);
		$table->addCell(null, $styleCell)->addText($totalSum, $fontStyle);

		// $section->addText('????????: ' . $totalSum . ' ????');

		$section->addText('');
		$section->addText('');
		$section->addText('_________________				                                ___________________');

		$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');

		$dateStr = ' ?????? ' . $nak->shop->name;
		$filename = '??????????????????' . $dateStr . '.docx';

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
