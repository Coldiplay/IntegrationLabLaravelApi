<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Library\ApiHelpers;
use App\Http\Requests\StoreIncidentRequest;
use App\Http\Requests\UpdateIncidentRequest;
use App\Http\Resources\IncidentCollection;
use App\Http\Resources\IncidentResource;
use App\Models\Incident;
use Illuminate\Http\JsonResponse;

class IncidentController extends Controller
{
    use ApiHelpers;
    /**
     * Display a listing of the resource.
     */
    public function index($driverId) : JsonResponse
    {
        $this->authorize('viewAny', Incident::class);
        $incidents = Incident::query()->where('driver_id', $driverId)->get();
        return $this->onSuccess(new IncidentCollection($incidents), 'Incidents retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreIncidentRequest $request) : JsonResponse
    {
        $this->authorize('create', Incident::class);
        $incident = Incident::create($request->validated());
        return $this->onSuccess(new IncidentResource($incident), 'Incident successfully created.', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Incident $incident) : JsonResponse
    {
        $this->authorize('view', $incident);
        return $this->onSuccess(new IncidentResource($incident), 'Incident successfully created.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateIncidentRequest $request, Incident $incident) : JsonResponse
    {
        $this->authorize('update', $incident);
        $incident->update($request->validated());
        return $this->onSuccess(new IncidentResource($incident), 'Incident successfully updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Incident $incident) : JsonResponse
    {
        $this->authorize('delete', $incident);
        $incident->delete();
        return $this->onSuccess(null, 'Incident successfully deleted.');
    }
}
