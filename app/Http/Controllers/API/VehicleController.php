<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Library\ApiHelpers;
use App\Http\Requests\StoreVehicleRequest;
use App\Http\Requests\UpdateVehicleRequest;
use App\Http\Resources\VehicleCollection;
use App\Http\Resources\VehicleResource;
use App\Models\Vehicle;
use Illuminate\Http\JsonResponse;

class VehicleController extends Controller
{
    use ApiHelpers;
    /**
     * Display a listing of the resource.
     */
    public function index() : JsonResponse
    {
        $this->authorize('viewAny', Vehicle::class);
        return $this->onSuccess(new VehicleCollection(Vehicle::all()), 'Vehicles retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreVehicleRequest $request)
    {
        $this->authorize('create', Vehicle::class);
        $vehicle = Vehicle::create($request->validated());
        return $this->onSuccess(new VehicleResource($vehicle), 'Vehicle created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Vehicle $vehicle)
    {
        $this->authorize('view', $vehicle);
        return $this->onSuccess(new VehicleResource($vehicle), 'Vehicle retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateVehicleRequest $request, Vehicle $vehicle)
    {
        $this->authorize('update', $vehicle);
        $vehicle->update($request->validated());
        return $this->onSuccess(new VehicleResource($vehicle), 'Vehicle updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vehicle $vehicle)
    {
        $this->authorize('delete', $vehicle);
        $vehicle->delete();
        return $this->onSuccess(null, 'Vehicle deleted successfully.');
    }
}
