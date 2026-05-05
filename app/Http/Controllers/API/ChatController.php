<?php

namespace App\Http\Controllers\API;

use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Http\Library\ApiHelpers;
use App\Http\Requests\StoreChatRequest;
use App\Http\Requests\StoreMessageRequest;
use App\Http\Requests\UpdateChatRequest;
use App\Http\Requests\UpdateMessageRequest;
use App\Http\Resources\ChatCollection;
use App\Http\Resources\ChatResource;
use App\Http\Resources\MessageResource;
use App\Http\Resources\MessagesCollection;
use App\Http\Resources\UserCollection;
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
    public function index(Request $request, $userId) : JsonResponse
    {
        if (empty($userId) && Role::isAdminFromValue($request->user()->role)) {
            $chats = Chat::all();
        }
        else{
            $chats = Chat::query()->whereExists(function ($query) use ($userId) {
                $query->select(DB::raw(1))->from('chat_members')->where('user_id', $userId);
            });
        }

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
        if (ChatMember::where('chat_id', $chat->id)
                ->where('user_id', $request->user()->id)
                ->exists()
            || Role::isAdminFromValue($request->user()->role))
        {
            return $this->onSuccess(new ChatResource($chat), 'Chat retrieved successfully.');
        }

        return $this->onError(401, 'You are not allowed to see this chat.');
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


    public function getMessages(Request $request, Chat $chat) : JsonResponse
    {
        if (ChatMember::where('chat_id', $chat->id)
            ->where('user_id', $request->user()->id)
            ->exists()) {
            $messages = Message::where('chat_id', $chat->id)->get();
            return $this->onSuccess(new MessagesCollection($messages),
                'Messages retrieved successfully.');
        }

        return $this->onError(401, 'You do not have permission to view this chat.');
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
    public function deleteMessage(Request $request, Message $message) : JsonResponse
    {
        if ($request->user()->id === $message->sender_id)
        //|| Role::isAdmin($request->user()->role))
        {
            $message->delete();
            return $this->onSuccess(null, 'Message deleted successfully.');
        }

        return $this->onError(401, 'You do not have permission to delete this chat.');
    }

    public function getMembers(Request $request, Chat $chat) : JsonResponse
    {
        if (ChatMember::where('chat_id', $chat->id)
            ->where('user_id', $request->user()->id)
            ->exists()
        || Role::isAdminFromValue($request->user()->role)) {
            return $this->onSuccess(new UserCollection($chat->chatMembers()->get()));
        }

        return $this->onError(401, 'You do not have permission to view this chat.');
    }
    public function addMember(Request $request, Chat $chat, User $user) : JsonResponse
    {
        //TODO: сделать отдельный StoreMemberRequest

        if (ChatMember::where('chat_id', $chat->id)
            ->where('user_id', $request->user()->id)
            ->exists()) {

            //TODO: Посмотреть про guard аттрибуты
            ChatMember::firstOrCreate(['user_id' => $user->id, 'chat_id' => $chat->id]);
            return $this->onSuccess(null, 'Chat member added successfully.', 201);
        }
        return $this->onError(401, 'You are not allowed to use this action.');
    }
    public function removeMember(Request $request, Chat $chat, User $user) : JsonResponse
    {
        if (Role::isAdminFromValue($request->user()->role) ||
            ($request->user()->id === $user->id && ChatMember::firstWhere([
                    ['user_id' => $user->id],
                    ['chat_id' => $chat->id]
                ])->exists()))
        {
            ChatMember::firstWhere([
                ['user_id' => $user->id],
                ['chat_id' => $chat->id]
            ])->delete();
            return $this->onSuccess(null, 'Chat member removed successfully.');
        }

        return $this->onError(401, 'You do not have permission to view this chat.');
    }
}
