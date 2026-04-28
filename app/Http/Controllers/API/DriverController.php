<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Library\ApiHelpers;
use App\Http\Requests\StoreDriverRequest;
use App\Http\Requests\UpdateDriverRequest;
use App\Http\Resources\DriverCollection;
use App\Http\Resources\DriverResource;
use App\Models\Driver;

class DriverController extends Controller
{
    use ApiHelpers;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->onSuccess(new DriverCollection(Driver::all()), 'Drivers retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDriverRequest $request)
    {
        $driver = Driver::create($request->validated());
        return $this->onSuccess(new DriverResource($driver), 'Driver registered successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Driver $driver)
    {
        return $this->onSuccess(new DriverResource($driver), 'Driver retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDriverRequest $request, Driver $driver)
    {
        $driver->update($request->validated());
        return $this->onSuccess(new DriverResource($driver), 'Driver updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Driver $driver)
    {
        $driver->delete();
        return $this->onSuccess(new DriverResource($driver), 'Driver deleted successfully.');
    }
}
