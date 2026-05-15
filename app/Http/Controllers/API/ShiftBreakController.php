<?php

namespace App\Http\Controllers\API;

use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Http\Library\ApiHelpers;
use App\Http\Requests\StoreShiftBreakRequest;
use App\Http\Requests\UpdateShiftBreakRequest;
use App\Http\Resources\ShiftBreakCollection;
use App\Http\Resources\ShiftBreakResource;
use App\Models\ShiftBreak;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use PhpParser\Builder;

class ShiftBreakController extends Controller
{
    use ApiHelpers;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) : JsonResponse
    {
        $this->authorize('viewAny', ShiftBreak::class);

        $user = $request->user();

        if (Role::isAdmin($user) || Role::isLogistician($user)) {
            return $this->onSuccess(new ShiftBreakCollection(ShiftBreak::all()));
        }

        return $this->onSuccess(new ShiftBreakCollection($user->driver->breaks()), 'Shift breaks retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreShiftBreakRequest $request) : JsonResponse
    {
        $this->authorize('create', ShiftBreak::class);
        $shiftBreak = ShiftBreak::create($request->validated());
        return $this->onSuccess(new ShiftBreakResource($shiftBreak), 'Shift break created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ShiftBreak $shiftBreak) : JsonResponse
    {
        $this->authorize('view', $shiftBreak);
        return $this->onSuccess(new ShiftBreakResource($shiftBreak), 'Shift break retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateShiftBreakRequest $request, ShiftBreak $shiftBreak) : JsonResponse
    {
        $this->authorize('update', $shiftBreak);
        $shiftBreak->update($request->validated());
        return $this->onSuccess(new ShiftBreakResource($shiftBreak), 'Shift break updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ShiftBreak $shiftBreak) : JsonResponse
    {
        $this->authorize('delete', $shiftBreak);
        $shiftBreak->delete();
        return $this->onSuccess(null, 'Shift break deleted successfully.');
    }
}
