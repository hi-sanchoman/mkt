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
    public function getOrders(Collection $realization_ids)
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
                'assortment' => $this->formAssortmentTo($realization_id, $products),
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
    private function formAssortmentTo(int $realization_id, Collection $storeProducts)
    {
        $assortment = [];

        $allReports = Report::where('realization_id', $realization_id)->get();

        foreach ($storeProducts as $product) {
            $reports = $allReports->where('assortment_id', $product->id);

            $assortment[] = [
                'name' => $product->type,
                'amount' => $reports->values(),
                'order_amount' => $reports->count() > 0
                    ? $reports->first()->order_amount
                    : 0
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
