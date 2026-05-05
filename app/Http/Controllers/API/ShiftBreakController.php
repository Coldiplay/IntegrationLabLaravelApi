<?php

namespace App\Http\Controllers\API;

use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Http\Library\ApiHelpers;
use App\Http\Requests\StoreShiftBreakRequest;
use App\Http\Requests\UpdateShiftBreakRequest;
use App\Http\Resources\ShiftBreakCollection;
use App\Models\DriversShift;
use App\Models\ShiftBreak;
use Illuminate\Http\Request;

class ShiftBreakController extends Controller
{
    use ApiHelpers;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $shiftId)
    {
        if (empty($shiftId) && Role::isAdmin($request->user())) {
            return $this->onSuccess(new ShiftBreakCollection(ShiftBreak::all()));
        }

        if (DriversShift::where('id', $shiftId)
            ->where('driver_id', $request->user()->id)
            ->exists()) {
            return $this->onSuccess(new ShiftBreakCollection(ShiftBreak::where('shift_id', $shiftId)), 'Shift breaks retrieved successfully.');
        }

        return $this->onError(401, 'You do not have permission to access this shift.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreShiftBreakRequest $request)
    {

    }

    /**
     * Display the specified resource.
     */
    public function show(ShiftBreak $shiftBreak)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateShiftBreakRequest $request, ShiftBreak $shiftBreak)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ShiftBreak $shiftBreak)
    {
        //
    }
}
