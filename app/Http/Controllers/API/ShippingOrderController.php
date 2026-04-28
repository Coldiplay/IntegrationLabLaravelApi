<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreShippingOrderRequest;
use App\Http\Requests\UpdateShippingOrderRequest;
use App\Models\ShippingOrder;

class ShippingOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreShippingOrderRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(ShippingOrder $shippingOrder)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateShippingOrderRequest $request, ShippingOrder $shippingOrder)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ShippingOrder $shippingOrder)
    {
        //
    }
}
