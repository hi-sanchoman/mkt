<?php

namespace App\Http\Controllers;

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

/**
 * 
 */
class RealizatorsController extends Controller
{
		
	public function index(){
		//$ids = Realization::selectRaw('max(id) as id, realizator')->where('realizator', Auth::user()->id)->groupBy('realizator')->pluck('id');
        $realizator = Auth::user();
 		$realcount = Realization::selectRaw('count(id) as amount, realizator')->groupBy('realizator')->with('realizator')->get();
 		
 		$myrealizations = Realization::
 			where('realizator', Auth::user()->id)
 			->with('realizator','order')
 			->orderBy('id', 'DESC')
 			->whereDay('created_at', now())
 			->get();

 		// dd($myrealizations->toArray());

		$assortment = Store::orderBy('num', 'asc')->get();
        //$realizations = Realization::whereIn('id',$ids)->with('order','realizator')->get();
        
        $assorder = [];
        $myassortment = [];
        foreach($assortment as $item){
        	$assorder[$item->id] = 0;
        	$myassortment[$item->id] = Store::find($item->id);
        }
        
        $nak_report = [];
        $products = Store::orderBy('num', 'asc')->get();
        foreach($products as $product){
        	$nak_report[] = [
        		'name' => $product->type,
        		'amount' => Grocery::whereYear('created_at', date('Y'))->whereMonth('created_at',date('m'))->whereIn('nak_id',Nak::where('user_id',Auth::user()->id)->pluck('id'))->where('assortment_id',$product->id)->sum('amount'),
        		'brak' => Grocery::whereYear('created_at', date('Y'))->whereMonth('created_at',date('m'))->whereIn('nak_id',Nak::where('user_id',Auth::user()->id)->pluck('id'))->where('assortment_id',$product->id)->sum('brak'),
        		'sum' => Grocery::whereYear('created_at', date('Y'))->whereMonth('created_at',date('m'))->whereIn('nak_id',Nak::where('user_id',Auth::user()->id)->pluck('id'))->where('assortment_id',$product->id)->sum('sum'),
        	];
        }




        
		// dd($myrealizations->toArray());

        $data = [
			//'realizations' => $realizations,
			'assortment' => $myassortment,
			'realcount' => $realcount,
			'realizator' => $realizator,
			'auth_realization' => $myrealizations,
			'assorder' => $assorder,
			'assorder1' => $assorder,
			'nakladnoe' => Nak::where('user_id',Auth::user()->id)->with('grocery')->get(),
			'shops' => Magazine::where('realizator',Auth::user()->id)->get(),
			'nak_report' => $nak_report,
			'report1' => Report::where('user_id',Auth::user()->id)->whereRaw('realization_id = (select max(`realization_id`) from reports)')->with('assortment')->get()->toArray(),
		];
		// dd($data);

		return Inertia::render('Orders/Index', $data);
	}

	public function newOrder() {
		$myrealizations = Realization::
 			where('realizator', Auth::user()->id)
 			->with('realizator','order')
 			->orderBy('id', 'DESC')
 			->whereDay('created_at', now())
 			->get();

		$assortment = Store::orderBy('num', 'asc')->get();
        //$realizations = Realization::whereIn('id',$ids)->with('order','realizator')->get();
        
        $assorder = [];
        $myassortment = [];
        foreach($assortment as $item){
        	$assorder[$item->id] = 0;
        	$myassortment[$item->id] = Store::find($item->id);
        }

        $data = [
			'assortment' => $myassortment,			
			'assorder1' => $assorder,
			'auth_realization' => $myrealizations
		];
		// dd($data);

		return Inertia::render('Orders/New_order', $data);
		
	}

	public function addOrder(){
		$myrealizations = Realization::
 			where('realizator', Auth::user()->id)
 			->with('realizator','order')
 			->orderBy('id', 'DESC')
 			->whereDay('created_at', now())
 			->get();

		$assortment = Store::orderBy('num', 'asc')->get();
        //$realizations = Realization::whereIn('id',$ids)->with('order','realizator')->get();
        
        $assorder = [];
        $myassortment = [];
        foreach($assortment as $item){
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






	public function history($id){
		$orders = Realization::where('realizator',$id)->with('order','realizator')->orderBy('id','desc')->get();
		$assortment = Store::orderBy('num', 'asc')->get();
		return Inertia::render('Orders/History',[
			'orders' => $orders,
			'assortment' => $assortment
		]);
	}

	public function mobHistory(Request $request) {
		$myrealizations = Realization::
 			where('realizator', Auth::user()->id)
 			->with('realizator','order')
 			->orderBy('id', 'DESC')
 			->whereDay('created_at', now())
 			->get();
		
		return Inertia::render('Orders/Istoriya',[
			'auth_realization' => $myrealizations,
		]);
	}

	public function mobAvans(Request $request) {
		$myrealizations = Realization::
 			where('realizator', Auth::user()->id)
 			->with('realizator','order')
 			->orderBy('id', 'DESC')
 			->whereDay('created_at', now())
 			->get();

 		$realizator = Auth::user();
		
		return Inertia::render('Orders/Avans',[

			'realizator' => $realizator,
			'report1' => Report::where('user_id',Auth::user()->id)->whereRaw('realization_id = (select max(`realization_id`) from reports)')->with('assortment')->get()->toArray(),
		]);
	}

	public function mobNakladnie(Request $request) {
		$myrealizations = Realization::
 			where('realizator', Auth::user()->id)
 			->with('realizator','order')
 			->orderBy('id', 'DESC')
 			->whereDay('created_at', now())
 			->get();

		$nak_report = [];
        $products = Store::orderBy('num', 'asc')->get();

		$assortment = Store::orderBy('num', 'asc')->get();

        foreach($products as $product) {
        	$nak_report[] = [
        		'name' => $product->type,
        		'amount' => Grocery::whereYear('created_at', date('Y'))->whereMonth('created_at',date('m'))->whereIn('nak_id',Nak::where('user_id',Auth::user()->id)->pluck('id'))->where('assortment_id',$product->id)->sum('amount'),
        		'brak' => Grocery::whereYear('created_at', date('Y'))->whereMonth('created_at',date('m'))->whereIn('nak_id',Nak::where('user_id',Auth::user()->id)->pluck('id'))->where('assortment_id',$product->id)->sum('brak'),
        		'sum' => Grocery::whereYear('created_at', date('Y'))->whereMonth('created_at',date('m'))->whereIn('nak_id',Nak::where('user_id',Auth::user()->id)->pluck('id'))->where('assortment_id',$product->id)->sum('sum'),
        	];
        }

        return Inertia::render('Orders/Naks',[
        	'auth_realization' => $myrealizations,
			'nakladnoe' => Nak::where('user_id',Auth::user()->id)->with('grocery')->get(),
			'shops' => Magazine::where('realizator',Auth::user()->id)->get(),
			'nak_report' => $nak_report,
			'assortment' => $assortment,
		]);
	}

	public function saveNak(Request $request){
		$nak = new Nak();
		$nak->user_id = Auth::user()->id;
		
		$magazine = Magazine::where('name', 'LIKE', $request->shop)->first();

		if ($magazine == null) {
			$magazine = new Magazine();
			$magazine->name = $request->shop;
			$magazine->realizator = Auth::user()->id;
			$magazine->timestamps = false;
			$magazine->save();
		}

		$nak->shop_id = $magazine->id;		
		$nak->consegnation = $request->option;
		$nak->realization_id = $request->realization_id;
		$nak->save();

		$mysum = 0; 
		foreach($request->items as $key => $value){
			$grocery = new Grocery();
			$grocery->nak_id = $nak->id;
        	$grocery->assortment_id = Store::where('type', 'LIKE', $value)->value('id');
        	$grocery->amount = $request->amounts[$key];
        	$grocery->brak = $request->brak[$key];
        	$grocery->price = Store::where('id', $grocery->assortment_id)->first()->price;
        	$grocery->sum = $grocery->price * $grocery->amount;
        	$grocery->save();

        	$mysum = $mysum + $grocery->sum;

        	$report = Report::where('realization_id', $request->realization_id)->where('user_id', Auth::user()->id)->where('assortment_id', $grocery->assortment_id)->first();

        	$report->sold += $grocery->amount;
        	$report->defect += $grocery->brak;
        	// $report->returned -= $grocery->amount;
			$report->returned = $report->amount - $report->sold - $report->defect;
        	$report->save();

        	if ($report->returned + $report->defect + $report->sold > $report->amount) {
        		$grocery->correct = 1;
        	}
        		
    		$grocery->save();
    		$nak->save();
		}

		$pivot = new Pivot();
		$pivot->realization_id = $request->realization_id;
		$pivot->magazine_id = $nak->shop_id;
		$pivot->sum = $mysum;
		$pivot->cash = $request->option == 1 ? 0 : 1;
		$pivot->save();


		return [
			'message' => 'Накладная сохранена'
		];
	}

	public function blank($id){
		$nak = Nak::find($id);
		$grocery = Grocery::where('nak_id',$nak->id)->get();

		if($nak->consegnation == 2){
			$consegnation = "Консегнация МКТ";
		}
		else{
			$consegnation =	"Консегнация";
		}
		$phpWord = new \PhpOffice\PhpWord\PhpWord();

		$paper = new \PhpOffice\PhpWord\Style\Paper();
		$paper->setSize('Letter');

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

        $section->addImage("img/logo4.png",$imageStyle);

        $section->addText('				СПК Майлыкент-Сут                                '.Magazine::find($nak->shop_id)->value('name'));

        $section->addText('				'.date('d/m/Y',strtotime($nak->created_at)).'       		                            '.$consegnation);

        $styleCell = array('borderTopSize'=>1 ,'borderTopColor' =>'black','borderLeftSize'=>1,'borderLeftColor' =>'black','borderRightSize'=>1,'borderRightColor'=>'black','borderBottomSize' =>1,'borderBottomColor'=>'black' );
        $fontStyle = array('italic'=> true, 'size'=>11, 'name'=>'Times New Roman','afterSpacing' => 0, 'Spacing'=> 0, 'cellMargin'=>0 );


        $TfontStyle = array('bold'=>true, 'italic'=> true, 'size'=>11, 'name' => 'Times New Roman', 'afterSpacing' => 0, 'Spacing'=> 0, 'cellMargin'=>0);

        $cfontStyle = array('allCaps'=>true,'italic'=> true, 'size'=>11, 'name' => 'Times New Roman','afterSpacing' => 0, 'Spacing'=> 0, 'cellMargin'=>0);

        $table = $section->addTable('myOwnTableStyle',array('borderSize' => 1, 'borderColor' => '999999', 'afterSpacing' => 0, 'Spacing'=> 0, 'cellMargin'=>0  ));

        $table->addRow();
		$table->addCell(2500,$styleCell)->addText('#',$TfontStyle);
		$table->addCell(6000,$styleCell)->addText("Атауы/Наименование",$cfontStyle);
		$table->addCell(1500,$styleCell)->addText('Кол-во',$TfontStyle);
		$table->addCell(2000,$styleCell)->addText("Брак",$fontStyle);
		$table->addCell(2000,$styleCell)->addText("Цена",$fontStyle);
		$table->addCell(2000,$styleCell)->addText("Сумма",$fontStyle);

		foreach($grocery as $key => $item){
			$p = Store::where('id',$item['assortment_id'])->value('type');
			$table->addRow();
			$table->addCell(2500,$styleCell)->addText($key+1,$TfontStyle);
			$table->addCell(6000,$styleCell)->addText($p,$cfontStyle);
			$table->addCell(1500,$styleCell)->addText($item['amount'],$TfontStyle);
			$table->addCell(2000,$styleCell)->addText($item['brak'],$fontStyle);
			$table->addCell(2000,$styleCell)->addText($item['price'],$fontStyle);
			$table->addCell(2000,$styleCell)->addText($item['sum'],$fontStyle);
		}

		for($i = count($grocery); $i < 21; $i++){
		
				$table->addRow();
				$table->addCell(2500,$styleCell)->addText($i+1,$TfontStyle);
				$table->addCell(6000,$styleCell)->addText("",$cfontStyle);
				$table->addCell(1500,$styleCell)->addText('',$TfontStyle);
				$table->addCell(2000,$styleCell)->addText("",$fontStyle);
				$table->addCell(2000,$styleCell)->addText("",$fontStyle);
				$table->addCell(2000,$styleCell)->addText("",$fontStyle);
			
		}
		$section->addText('');
		$section->addText('');
		$section->addText('_________________				                                ___________________');

        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');

        try {

            $objWriter->save(storage_path('blank.docx'));

        } catch (Exception $e) {

        }



        // return response()->download(storage_path('blank.docx'));

		
        return response()->download(storage_path('mkt-nak.docx'));
	}

	public function nakStatus(){
		Nak::where('finished','0')->update(['finished' => '1']);
	}

	public function getNakReportByMonth(Request $request){
		
		$nak_report = [];
        $products = Store::orderBy('num', 'asc')->get();
        foreach($products as $product){
        	$nak_report[] = [
        		'name' => $product->type,
        		'amount' => Grocery::whereYear('created_at', date('Y'))->whereMonth('created_at',$request->month)->whereIn('nak_id',Nak::where('user_id',Auth::user()->id)->pluck('id'))->where('assortment_id',$product->id)->sum('amount'),
        		'brak' => Grocery::whereYear('created_at', date('Y'))->whereMonth('created_at',$request->month)->whereIn('nak_id',Nak::where('user_id',Auth::user()->id)->pluck('id'))->where('assortment_id',$product->id)->sum('brak'),
        		'sum' => Grocery::whereYear('created_at', date('Y'))->whereMonth('created_at',$request->month)->whereIn('nak_id',Nak::where('user_id',Auth::user()->id)->pluck('id'))->where('assortment_id',$product->id)->sum('sum'),
        	];
        }

		return $nak_report;
	}
}