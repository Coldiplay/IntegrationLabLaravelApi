<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Library\ApiHelpers;
use App\Http\Requests\StoreShippingRequest;
use App\Http\Requests\UpdateShippingRequest;
use App\Http\Resources\ShippingCollection;
use App\Http\Resources\ShippingResource;
use App\Models\Shipping;
use Illuminate\Http\JsonResponse;

class ShippingController extends Controller
{
    use ApiHelpers;
    /**
     * Display a listing of the resource.
     */
    public function index($driverId) : JsonResponse
    {
        $shippings = Shipping::query()->where('driver_id', $driverId)->get();
        return $this->onSuccess(new ShippingCollection($shippings), 'Shippings retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreShippingRequest $request) : JsonResponse
    {
        $shipping = Shipping::create($request->validated());
        return $this->onSuccess($shipping, 'Shipping created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Shipping $shipping) : JsonResponse
    {
        return $this->onSuccess(new ShippingResource($shipping), 'Shipping retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateShippingRequest $request, Shipping $shipping) : JsonResponse
    {
        $shipping->update($request->validated());
        return $this->onSuccess($shipping, 'Shipping updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Shipping $shipping) : JsonResponse
    {
        $shipping->delete();
        return $this->onSuccess(null, 'Shipping deleted successfully.');
    }

    public function confirmStart(Shipping $shipping) : JsonResponse
    {
        $shipping->update(['shipped_date' => now()]);
        return $this->onSuccess(new ShippingResource($shipping), 'Shipping updated successfully.');
    }

    public function confirmEnd(Shipping $shipping) : JsonResponse
    {
        $shipping->update(['delivery_date' => null]);
        return $this->onSuccess(new ShippingResource($shipping), 'Shipping updated successfully.');
    }
}
