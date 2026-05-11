<?php

namespace App\Http\Controllers\API;

use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Http\Library\ApiHelpers;
use App\Http\Requests\StoreShippingRequest;
use App\Http\Requests\UpdateShippingRequest;
use App\Http\Resources\ShippingCollection;
use App\Http\Resources\ShippingResource;
use App\Models\Shipping;
use Illuminate\Http\JsonResponse;
use Request;

class ShippingController extends Controller
{
    use ApiHelpers;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) : JsonResponse
    {
        $this->authorize('viewAny', [Shipping::class]);
        $user = $request->user();
        if (Role::isLogistician($user) || Role::isAdmin($user)) {
            return $this->onSuccess(new ShippingCollection(Shipping::all()));
        }
        $shippings = Shipping::query()->where('driver_id', $user->id)->get();
        return $this->onSuccess(new ShippingCollection($shippings), 'Shippings retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreShippingRequest $request) : JsonResponse
    {
        $this->authorize('create', [Shipping::class]);
        $shipping = Shipping::create($request->validated());
        return $this->onSuccess($shipping, 'Shipping created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Shipping $shipping) : JsonResponse
    {
        $this->authorize('view', [Shipping::class, $shipping]);
        return $this->onSuccess(new ShippingResource($shipping), 'Shipping retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateShippingRequest $request, Shipping $shipping) : JsonResponse
    {
        $this->authorize('update', [Shipping::class, $shipping]);
        $shipping->update($request->validated());
        return $this->onSuccess($shipping, 'Shipping updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Shipping $shipping) : JsonResponse
    {
        $this->authorize('delete', [Shipping::class, $shipping]);
        $shipping->delete();
        return $this->onSuccess(null, 'Shipping deleted successfully.');
    }

    public function confirmStart(Shipping $shipping) : JsonResponse
    {
        $this->authorize('change-start-end-state', [Shipping::class, $shipping]);
        $shipping->update(['shipped_date' => now()]);
        return $this->onSuccess(new ShippingResource($shipping), 'Shipping updated successfully.');
    }

    public function confirmEnd(Shipping $shipping) : JsonResponse
    {
        $this->authorize('change-start-end-state', [Shipping::class, $shipping]);
        //TODO: Какая-то хрень, похже посмотреть
        $shipping->update(['delivery_date' => null]);
        return $this->onSuccess(new ShippingResource($shipping), 'Shipping updated successfully.');
    }
}
