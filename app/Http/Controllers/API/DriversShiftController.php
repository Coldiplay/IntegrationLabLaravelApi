<?php

namespace App\Http\Controllers\API;

use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Http\Library\ApiHelpers;
use App\Http\Requests\StoreDriversShiftRequest;
use App\Http\Requests\UpdateDriversShiftRequest;
use App\Http\Resources\DriversShiftCollection;
use App\Http\Resources\DriversShiftResource;
use App\Models\DriversShift;
use Illuminate\Http\Request;

class DriversShiftController extends Controller
{
    use ApiHelpers;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('view-any', DriversShift::class);
        $user = $request->user();
        if (Role::isAdmin($user) || Role::isLogistician($user)) {
            $shifts = DriversShift::all();
        }
        elseif (Role::isDriver($user)) {
            $shifts = DriversShift::where('driver_id', $user->id)->get();
        }

        return $this->onSuccess(new DriversShiftCollection($shifts), "Shifts retrieved successfully.");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDriversShiftRequest $request)
    {
        $this->authorize('create', [DriversShift::class]);
        $shift = DriversShift::create($request->validated());
        return $this->onSuccess(new DriversShiftResource($shift), 'Shift updated successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(DriversShift $driversShift)
    {
        $this->authorize('view', $driversShift);
        return $this->onSuccess(new DriversShiftResource($driversShift), 'Shift retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDriversShiftRequest $request, DriversShift $driversShift)
    {
        $this->authorize('update', [DriversShift::class, $driversShift]);
        $data = $request->validated();
        if (Role::isDriver($request->user()))
        {
            $data['end'] = now();
        }
        $driversShift->update($request->validated());

        return $this->onSuccess(new DriversShiftResource($driversShift), 'Shift updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DriversShift $driversShift)
    {
        $this->authorize('delete', $driversShift);
        $driversShift->delete();
        return $this->onSuccess(null, 'Shift deleted successfully.');
    }
}
