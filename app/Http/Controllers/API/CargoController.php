<?php

namespace App\Http\Controllers\API;

use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Http\Library\ApiHelpers;
use App\Http\Requests\StoreCargoRequest;
use App\Http\Requests\UpdateCargoRequest;
use App\Http\Resources\CargoCollection;
use App\Http\Resources\CargoResource;
use App\Models\Cargo;
use App\Models\Shipping;
use Illuminate\Http\Request;

class CargoController extends Controller
{
    use ApiHelpers;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('view', Cargo::class);
        $user = $request->user();
        if (Role::isLogistician($user) || Role::isAdmin($user)) {
            $cargos = Cargo::all();
        }
        elseif(Role::isDriver($user)){
            $cargos = Shipping::with('cargos')
                ->where('driver_id',$user->id)
                ->get()
                ->pluck('cargos')
                ->flatten();
        }
        return $this->onSuccess(new CargoCollection($cargos), 'Cargos retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCargoRequest $request)
    {
        $this->authorize('create', Cargo::class);
        $cargo = Cargo::create($request->validated());
        return $this->onSuccess(new CargoResource($cargo), 'Cargo created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Cargo $cargo)
    {
        $this->authorize('view', [Cargo::class, $cargo]);
        return $this->onSuccess(new CargoResource($cargo), 'Cargo retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCargoRequest $request, Cargo $cargo)
    {
        $this->authorize('update', [Cargo::class, $cargo]);
        $cargo->update($request->validated());
        return $this->onSuccess(new CargoResource($cargo), 'Cargo updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cargo $cargo)
    {
        $this->authorize('delete', [Cargo::class, $cargo]);
        $cargo->delete();
        return $this->onSuccess(null, 'Cargo deleted successfully.');
    }
}
