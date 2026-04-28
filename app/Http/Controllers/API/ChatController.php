<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Library\ApiHelpers;
use App\Http\Requests\StoreChatRequest;
use App\Http\Requests\StoreMessageRequest;
use App\Http\Requests\UpdateChatRequest;
use App\Http\Requests\UpdateMessageRequest;
use App\Http\Resources\ChatCollection;
use App\Http\Resources\ChatResource;
use App\Http\Resources\MessageResource;
use App\Models\Chat;
use App\Models\ChatMember;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{
    use ApiHelpers;

    /**
     * Display a listing of the resource.
     */
    public function index($userId) : JsonResponse
    {
        //TODO: Сделать админа для просмотра всех чатов...?

        $chats = Chat::query()->whereExists(function ($query) use ($userId) {
            $query->select(DB::raw(1))->from('chat_members')->where('user_id', $userId);
        });

        return $this->onSuccess(new ChatCollection($chats), 'Chats retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreChatRequest $request) : JsonResponse
    {
        $chat = Chat::create($request->validated());
        ChatMember::create(['chat_id' => $chat->id, 'user_id' => $request->user()->id]);
        return $this->onSuccess(new ChatResource($chat), 'Chat created successfully.', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Chat $chat) : JsonResponse
    {
        return $this->onSuccess(new ChatResource($chat), 'Chat retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateChatRequest $request, Chat $chat) : JsonResponse
    {
        $chat->update($request->validated());
        return $this->onSuccess(new ChatResource($chat), 'Chat updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Chat $chat) : JsonResponse
    {
        $chat->delete();
        return $this->onSuccess(null, 'Chat deleted successfully.');
    }


    public function sendMessage(StoreMessageRequest $request) : JsonResponse
    {
        $data = $request->validated();
        $message = Chat::find($data['chat_id'])->messages()->create($data);
        return $this->onSuccess(new MessageResource($message), 'Message sent successfully.', 201);
    }
    public function updateMessage(UpdateMessageRequest $request, Message $message) : JsonResponse
    {
        $message->update($request->validated());
        return $this->onSuccess(new MessageResource($message), 'Message updated successfully.');
    }
    public function deleteMessage(Message $message) : JsonResponse
    {
        $message->delete();
        return $this->onSuccess(null, 'Message deleted successfully.');
    }

    public function addMember(Chat $chat, User $user) : JsonResponse
    {
        //TODO: Посмотреть про guard аттрибуты
        ChatMember::create(['user_id' => $user->id, 'chat_id' => $chat->id]);
        return $this->onSuccess(null, 'Chat member added successfully.', 201);
    }
    public function removeMember(Chat $chat, User $user) : JsonResponse
    {
        ChatMember::firstWhere([
            ['user_id' => $user->id],
            ['chat_id' => $chat->id]
        ])->delete();
        return $this->onSuccess(null, 'Chat member removed successfully.');
    }
}
