<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Library\ApiHelpers;
use App\Http\Requests\StoreShippingOrderRequest;
use App\Http\Requests\UpdateShippingOrderRequest;
use App\Http\Resources\ShippingOrderCollection;
use App\Http\Resources\ShippingOrderResource;
use App\Models\ShippingOrder;

class ShippingOrderController extends Controller
{
    use ApiHelpers;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', ShippingOrder::class);
        return $this->onSuccess(new ShippingOrderCollection(ShippingOrder::all()), 'All shipping orders retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreShippingOrderRequest $request)
    {
        $this->authorize('create', ShippingOrder::class);
        $shipping = ShippingOrder::create($request->validated());
        return $this->onSuccess(new ShippingOrderResource($shipping), 'Shipping created successfully.', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(ShippingOrder $shippingOrder)
    {
        $this->authorize('view', $shippingOrder);
        return $this->onSuccess(new ShippingOrderResource($shippingOrder), 'Shipping retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateShippingOrderRequest $request, ShippingOrder $shippingOrder)
    {
        $this->authorize('update', $shippingOrder);
        $shippingOrder->update($request->validated());
        return $this->onSuccess(new ShippingOrderResource($shippingOrder), 'Shipping updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ShippingOrder $shippingOrder)
    {
        $this->authorize('delete', $shippingOrder);
        $shippingOrder->delete();
        return $this->onSuccess(null, 'Shipping deleted successfully.');
    }
}
