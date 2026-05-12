<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Library\ApiHelpers;
use App\Http\Requests\UpdateMessageRequest;
use App\Http\Resources\MessageResource;
use App\Models\Message;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    use ApiHelpers;
    /**
     * Display the specified resource.
     */
    public function show(Message $message) : JsonResponse
    {
        $this->authorize('view', $message);
        return $this->onSuccess(new MessageResource($message), 'Message retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMessageRequest $request, Message $message) : JsonResponse
    {
        $this->authorize('update', $message);
        $message->update($request->validated());
        return $this->onSuccess(new MessageResource($message), 'Message updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Message $message) : JsonResponse
    {
        $this->authorize('delete', $message);
        $message->delete();
        return $this->onSuccess(null, 'Message deleted successfully.');
    }
}
