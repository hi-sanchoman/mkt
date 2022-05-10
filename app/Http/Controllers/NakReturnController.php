<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NakReturn;

class NakReturnController extends Controller
{
    public function store(Request $request) {
        NakReturn::create($request->all());

        $returns = NakReturn::query()
            ->with('oweshop')
            ->where('oweshop_id', $request->oweshop_id)
            ->where('realization_id', $request->realization_id)
            ->get();

        return ['data' => $returns];
    }
}
