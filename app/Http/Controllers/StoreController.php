<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use App\Models\Store;
use Illuminate\Http\Request;
use App\Models\Weightstore;
use App\Models\Freezer;
use App\Models\Tara;
use App\Models\User;
use App\Models\Percent;
use App\Models\PercentStorePivot;
use App\Models\Transaction;
use Illuminate\Support\Facades\Redirect;

/**
 * 
 */
class StoreController extends Controller
{
		
	public function index(){
		$tara = Tara::with('store')->get();
		$goods = Store::orderBy('num', 'asc')->get();
		$weight = Weightstore::where('id','!=','2')->where('id','!=','3')->get();
		$freezer = Freezer::all();
		$realizators = User::where('position_id','3')->get();
		
		$percents = Percent::orderBy('amount')->get();
		$pivotPrices = PercentStorePivot::get();

		foreach ($goods as $key => $item) {
			// dd($item->toArray());
			$percentArr = [];

			foreach ($percents as $percent) {
				$percentArr[$percent->id] = [
					'amount' => $percent->amount,
					'price' => $this->_getPivotPrice($percent, $item, $pivotPrices)
				];
			}

			$item->percents = $percentArr;
			// dd($item->toArray());
		}

		// dd($goods->toArray());

		return Inertia::render('Store/Index',[
            'goods' => $goods,
			'percents' => $percents,
            'weightgoods' => $weight,
            'freezer' => $freezer,
            'tara1' => $tara,
            'realizators' => $realizators,
			'pivotPrices' => $pivotPrices,
        ]);
	}

	private function _getPivotPrice($percent, $item, $pivotPrices) {
		foreach ($pivotPrices as $pivot) {
			if ($pivot->percent_id == $percent->id && $pivot->store_id == $item->id) {
				return $pivot->price;
			}
		}

		return 0;
	}

	public function update(Request $request, $id) {
		
		$sklad = $request->form['sklad'];
		$name = $request->form['name'];
		$isZakvaska = $request->form['is_zakvaska'];

		if ($sklad == 'store') {
			$item = Store::findOrFail($id);
			$item->type = $name;
			$item->save();
 		} else if ($sklad == 'weight') {
			$item = Weightstore::findOrFail($id);
			$item->assortment = $name;
			$item->save();
 		} else if ($sklad == 'freezer') {
			$item = Freezer::findOrFail($id);
			$item->assortment = $name;
			$item->is_zakvaska = $isZakvaska;
			$item->save();
 		} else if ($sklad == 'tara') {
			$item = Tara::findOrFail($id);
			$item->name = $name;
			$item->save();
 		} 
		
		return 1;
	}

	public function destroy(Request $request) {
		$toBeDeleted = null;

		if ($request->sklad == 'store')
			$toBeDeleted = Store::find($request->item['id']);
		else if ($request->sklad == 'weight')
			$toBeDeleted = Weightstore::find($request->item['id']);
		else if ($request->sklad == 'freezer')
			$toBeDeleted = Freezer::find($request->item['id']);
		else if ($request->sklad == 'tara')
			$toBeDeleted = Tara::find($request->item['id']);

        if ($toBeDeleted == null) {
            return 0;
        }

        $toBeDeleted->delete();

        return 1;
	}



	public function add(Request $request){
		$store = Store::find($request->assortment);
		$store->amount = $request->amount;
		$store->sum = $request->amount * $store->price;
		$store->save();

		return $store;
	}

	public function addPrice(Request $request){
		$store = Store::find($request->assortment);
		$store->price = $request->price;
		// $store->sum = $request->price * $store->amount;
		$store->save();

		return $store;
	}

	public function addPivotPrice(Request $request){
		$store = Store::find($request->assortment);
		$percent = Percent::find($request->percent);
		
		PercentStorePivot::query()
			->where('percent_id', $percent->id)
			->where('store_id', $store->id)
			->update(['price' => $request->price]);

		// return $store;
	}

	public function addPriceZavod(Request $request){
		$store = Store::find($request->assortment);
		$store->price_zavod = $request->price_zavod;
		$store->sum = $request->price_zavod * $store->amount;
		$store->save();

		return $store;
	}

	public function addWeight(Request $request){
		$store = Weightstore::find($request->assortment);
		$store->price = $request->price;
		// $store->sum = $request->price * $store->amount;
		$store->save();

		return $store;
	}

	public function addWeightZavod(Request $request){
		$store = Weightstore::find($request->assortment);
		$store->price_zavod = $request->price_zavod;
		$store->sum = $request->price_zavod * $store->amount;
		$store->save();

		return $store;
	}

	public function addTaraAmount(Request $request){
		$store = Tara::find($request->id);
		$store->amount = $request->amount;
		$store->total = $request->amount * $store->inside;
		$store->sum = $store->price * $store->total;
		$store->save();

		return $store;
	}

	public function addTaraInside(Request $request){
		$store = Tara::find($request->id);
		$store->inside = $request->inside;
		$store->total = $request->inside * $store->amount;
		$store->sum = $store->price * $store->total;
		$store->save();

		return $store;
	}

	public function addTaraPrice(Request $request){
		$store = Tara::find($request->id);
		$store->price = $request->price;
		$store->sum = $request->price * $store->total;
		$store->save();

		return $store;
	}

	public function setFreezerPrice(Request $request){
		$freezer = Freezer::find($request->id);
		$freezer->price = $request->price;
		$freezer->save();
	}

	public function setFreezerAmount(Request $request){
		$freezer = Freezer::find($request->id);
		$freezer->amount = $request->amount;
		$freezer->save();
	}

	public function Swap() {
		return redirect('sklad/weight');
	}

	public function SwapFreezer(){
		$freezers = Freezer::all();
		$actions = Transaction::orderBy("id","desc")->where('sklad_id', 2)->get();
		return Inertia::render('Sklad/Freezer',['freezers' => $freezers, 'actions' => $actions]);
	}

	public function SwapWeight(){
		$goods = Weightstore::all();
		$actions = Transaction::orderBy("id","desc")->where('sklad_id', 1)->get();
		return Inertia::render('Sklad/Weight',['products' => $goods, 'actions' => $actions]);
	}

	public function addSklad(Request $request){
		if ($request->operation == "Приход"){
			$item = Weightstore::find($request->id);

			$item->amount = $item->amount + $request->amount;
			$item->save();

			$action = new Transaction();
			$action->t_from = Auth::user()->first_name;
			$action->t_to = $item->assortment;
			$action->amount = $request->amount;
			$action->sklad_id = 1;
			$action->type = $request->operation;
			$action->description = $request->description;
			$action->save();
			
			return ['action' => $action, 'message' => 'Продукт добавлен'];
		} else if ($request->operation == "В морозильник") {
			$item = Weightstore::find($request->id);
			$item->amount = $item->amount - $request->amount;
			$item->save();

			$item1 = Freezer::where('assortment','like','%'.$item->assortment.'%')->first();

			if ($item1 === null) {
				$item1 = new Freezer();
				$item1->assortment = $item->assortment;
				$item1->amount = $request->amount;
				$item1->price = $item->price;
				$item1->sum = $request->amount * $item->price;
				$item1->save();
			} else {
				$item1->amount = $item1->amount + $request->amount;
				$item1->save();
			}

			$action = new Transaction();
			$action->t_from = Auth::user()->first_name;
			$action->t_to = $item->assortment;
			$action->amount = $request->amount;
			$action->sklad_id = 1;
			$action->type = $request->operation;
			$action->description = $request->description;
			$action->save();
			
			return ['action' => $action, 'message' => 'Продукт перемещен'];
		} else if ($request->operation == "Расход") {
			$item = Weightstore::find($request->id);
			$item->amount = $item->amount - $request->amount;
			$item->save();

			$action = new Transaction();
			$action->t_from = Auth::user()->first_name;
			$action->t_to = $item->assortment;
			$action->amount = $request->amount;
			$action->sklad_id = 1;
			$action->type = $request->operation;
			$action->description = $request->description;
			$action->save();

			return ['action' => $action, 'message' => 'Продукт извлечен'];
		} else {
			return ['error' => "Введены не полные данные"];
		}
	}

	public function addFreezer(Request $request){
		if ($request->operation == "Приход") {
			$item = Freezer::find($request->id);
			$item->amount = $item->amount + $request->amount;
			$item->save();

			$action = new Transaction();
			$action->t_from = Auth::user()->first_name;
			$action->t_to = $item->assortment;
			$action->amount = $request->amount;
			$action->sklad_id = 2;
			$action->type = $request->operation;
			$action->description = $request->description;
			$action->save();
			
			return ['action' => $action, 'message' => 'Продукт добавлен'];
		} else if($request->operation == "Весовой склад") {
			$item = Freezer::find($request->id);
			$item->amount = $item->amount - $request->amount;
			$item->save();

			$item1 = Weightstore::where('assortment','like','%'.$item->assortment.'%')->first();
			
			if ($item1 === null) {
				$item1 = new Weightstore();
				$item1->assortment = $item->assortment;
				$item1->amount = $request->amount;
				$item1->price = $item->price;
				$item1->sum = $request->amount * $item->price;
				$item1->save();
			} else {
				$item1->amount = $item1->amount + $request->amount;
				$item1->save();
			}

			$action = new Transaction();
			$action->t_from = Auth::user()->first_name;
			$action->t_to = $item->assortment;
			$action->amount = $request->amount;
			$action->sklad_id = 2;
			$action->type = $request->operation;
			$action->description = $request->description;
			$action->save();

			return ['action' => $action, 'message' => 'Продукт перемещен'];
		} else if ($request->operation == "Расход") {
			$item = Freezer::find($request->id);
			$item->amount = $item->amount - $request->amount;
			$item->save();

			$action = new Transaction();
			$action->t_from = Auth::user()->first_name;
			$action->t_to = $item->assortment;
			$action->amount = $request->amount;
			$action->sklad_id = 2;
			$action->type = $request->operation;
			$action->description = $request->description;
			$action->save();

			return ['action' => $action, 'message' => 'Продукт извлечен'];
		} else {
			return ['error' => "Введены не полные данные"];
		}
	}

	public function addNewProduct(Request $request){
		$list = [];
		$message = "";
		
		if ($request->sklad == 'Продукция') {
			$store = new Store();
			$store->type = $request->product;
			$store->num = Store::orderBy('num', 'desc')->first()->num + 100;
			$store->price_zavod = 0;
			$store->save();

			$list = Store::orderBy('num', 'asc')->get();
			$message = "Добавлен новый продукт на склад готовой продукции";
		}
		else if ($request->sklad == 'Морозильник'){
			$freezer = new Freezer();
			$freezer->assortment = $request->product;
			$freezer->is_zakvaska = $request->is_zakvaska ? 1 : 0;
			$freezer->save();

			$list = Freezer::all();
			$message = "Добавлен новый продукт в морозильник";
		}
		else if($request->sklad == 'Весовой склад'){
			$weight = new Weightstore();
			$weight->assortment = $request->product;
			$weight->save();
			
			$list = Weightstore::all();
			$message = "Добавлен новый продукт в весовой склад";
		}else{
			$tara = new Tara();
			$tara->name = $request->product;
			$tara->save();

			$list = Tara::all();
			$message = "Добавлен новый продукт на склад тары и этикеток";
		}
		
		return ['message' => $message,'list' => $list];
	}

	public function getSklad(){
		$store = Store::orderBy('num', 'asc')->get();
		return Inertia::render('Store/Sklad',[
			'store' => $store
		]);
	}

	public function getFreezer(){
		$freezer = Freezer::all();
		return Inertia::render('Store/Freezer',[
			'freezer' => $freezer
		]);
	}

	public function getWeight(){
		$weight = Weightstore::all();
		return Inertia::render('Store/Weight',[
			'weight' => $weight,
			'weightgoods' => Weightstore::where('id','!=','2')->where('id','!=','3')->get()
		]);
	}

	public function getTara(){
		$tara = Tara::all();
		return Inertia::render('Store/Tara',[
			'tara' => $tara
		]);
	}
}