<?php

namespace App\Services;

use Illuminate\Support\Collection;
use App\Models\Realization;
use App\Models\Report;
use App\Models\Store;
use App\Models\User;

class RealizationService
{
    /**
     * @return array
     */
    public function getOrders(Collection $realization_ids, $autofillAmount = true)
    {
        $orders = [];
		$products = Store::orderBy('num', 'asc')->get();

        $realizations = Realization::notProduced() // Кажется notProduced() тут лишний
            ->whereIn('id', $realization_ids->toArray() )
            ->get();

		foreach ($realization_ids as $realization_id) {

            $real = $realizations->where('id', $realization_id)->first();
            if(!$real) continue;

			$orders[] = [
                'assortment' => $this->formAssortmentTo($realization_id, $products, $autofillAmount),
				'realizator' => User::find($real->realizator),
				'percent' => $real->percent,
				'status' => $real->status,
				'updated' => $real->updated_at,
				'id' => $real->id,
				'real' => $real,
			];
		}

        return $orders;
    }

    /**
     * Helper for RealizationService::getOrders()
     *
     * @return array
     */
    private function formAssortmentTo(int $realization_id, Collection $storeProducts, $autofillAmount = true)
    {
        $assortment = [];

        $allReports = Report::where('realization_id', $realization_id)->get();

        foreach ($storeProducts as $product) {
            $reports = $allReports->where('assortment_id', $product->id);

            $order_amount = $reports->count() > 0
                ? $reports->first()->order_amount
                : 0;

            $amount = $reports->count() > 0 ? $reports->values() : [[
                'order_amount' => 0,
                'amount' => 0, 
                'realization_id' => $realization_id,
                'assortment_id' => $product->id,
                'returned' => 0,
                'defect' => 0,
                'defect_sum' => 0,
                'sold' => 0
            ]];

            if($amount->length > 0 && $autofillAmount) { // Костыль: заполняем заранее для удобства клиента: чтобы не заполнять вручную, так как заполняют его раз в день
                $amount[0]['amount'] = $order_amount;
            }

            $assortment[] = [
                'name' => $product->type,
                'amount' => $amount,
                'order_amount' => $order_amount
            ];
        }

        return $assortment;
    }

    /**
     * @return Collection
     */
    public function quantityOfDistributorsRealizations()
    {
        return Realization::selectRaw('count(id) as amount, realizator')
            ->groupBy('realizator')
            ->with('realizator')
            ->get();
    }

    /**
     * @return Collection
     */
    public function getWaitingDistributorsRealizations()
    {
        return Realization::selectRaw('max(id) as id, realizator')
            ->notProduced()
            ->groupBy('realizator')
            ->pluck('id');
    }
}
