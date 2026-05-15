<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Library\ApiHelpers;
use App\Http\Requests\UpdateDriversShiftRequest;
use App\Http\Resources\ShippingResource;
use App\Models\DriversShift;
use App\Models\Shipping;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DriversShiftController extends Controller
{
    use ApiHelpers;
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
    public function store(UpdateDriversShiftRequest $request, Shipping $shipping)
    {
        $this->authorize('create', [DriversShift::class, $shipping]);
        $shipping->update(['shipped_date' => now()]);
        return $this->onSuccess(new ShippingResource($shipping), 'Shipping updated successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(DriversShift $driversShift)
    {

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DriversShift $driversShift)
    {
        $this->authorize('update', [DriversShift::class, $driversShift]);
        //TODO: Какая-то хрень, похже посмотреть

        //$shipping->update(['delivery_date' => null]);
        //return $this->onSuccess(new ShippingResource($shipping), 'Shipping updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DriversShift $driversShift)
    {
        //
    }
}
