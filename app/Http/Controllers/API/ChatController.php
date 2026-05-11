<?php

namespace App\Http\Controllers\API;

use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Http\Library\ApiHelpers;
use App\Http\Requests\StoreChatMemberRequest;
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
    public function index(Request $request) : JsonResponse
    {
        $this->authorize('viewAllUserChat', Chat::class);

        $user = $request->user();

        if (Role::isAdmin($user)) {
            $chats = Chat::all();
        }
        else {
            $chats = Chat::query()->whereExists(function ($query) use ($user) {
                $query->select(DB::raw(1))->from('chat_members')->where('user_id', $user->id);
            })->get();
        }

        return $this->onSuccess(new ChatCollection($chats), 'Chats retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreChatRequest $request) : JsonResponse
    {
        $this->authorize('create', Chat::class);

        $chat = Chat::create($request->validated());
        ChatMember::create(['chat_id' => $chat->id, 'user_id' => $request->user()->id]);
        return $this->onSuccess(new ChatResource($chat), 'Chat created successfully.', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Chat $chat) : JsonResponse
    {
        $this->authorize('view', $chat);

        return $this->onSuccess(new ChatResource($chat), 'Chat retrieved successfully.');
        /*
        if ($chat->chatMembers()
                ->where('user_id', $request->user()->id)
                ->exists()
            || Role::isAdmin($request->user()))
        {
            return $this->onSuccess(new ChatResource($chat), 'Chat retrieved successfully.');
        }

        return $this->onError(401, 'You are not allowed to see this chat.');
        */
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateChatRequest $request, Chat $chat) : JsonResponse
    {
        $this->authorize('update', $chat);
        $chat->update($request->validated());
        return $this->onSuccess(new ChatResource($chat), 'Chat updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Chat $chat) : JsonResponse
    {
        $this->authorize('delete', $chat);
        $chat->delete();
        return $this->onSuccess(null, 'Chat deleted successfully.');
    }


    public function getMessages(Request $request, Chat $chat) : JsonResponse
    {
        //TODO: Подумать, в какую политику запихнуть это
        if (ChatMember::where('chat_id', $chat->id)
            ->where('user_id', $request->user()->id)
            ->exists()) {
            $messages = Message::where('chat_id', $chat->id)->get();
            return $this->onSuccess(new MessagesCollection($messages),
                'Messages retrieved successfully.');
        }

        return $this->onError(401, 'You do not have permission to view this chat.');
    }
    public function sendMessage(StoreMessageRequest $request, Chat $chat) : JsonResponse
    {
        $this->authorize('create', [Message::class, $chat]);
        $data = $request->validated();
        $data['sender_id'] = $request->user()->id;
        $message = $chat->messages()->create($data);
        return $this->onSuccess(new MessageResource($message), 'Message sent successfully.', 201);
    }
    public function updateMessage(UpdateMessageRequest $request, Message $message) : JsonResponse
    {
        $this->authorize('update', Message::class);
        $message->update($request->validated());
        return $this->onSuccess(new MessageResource($message), 'Message updated successfully.');
    }
    public function deleteMessage(Message $message) : JsonResponse
    {
        $this->authorize('delete', $message);
        $message->delete();
        return $this->onSuccess(null, 'Message deleted successfully.');

        /*
        if ($request->user()->id === $message->sender_id)
        //|| Role::isAdmin($request->user()->role))
        {
            $message->delete();
            return $this->onSuccess(null, 'Message deleted successfully.');
        }

        return $this->onError(401, 'You do not have permission to delete this chat.');
        */
    }

    public function getMembers(Request $request, Chat $chat) : JsonResponse
    {
        $this->authorize('view', [ChatMember::class, $chat]);
        return $this->onSuccess(new UserCollection($chat->chatMembers()->get()));
    }
    public function addMember(StoreChatMemberRequest $request, Chat $chat) : JsonResponse
    {
        $this->authorize('create', [ChatMember::class, $request->user(), $chat]);
        $user_id = $request->validated()['user_id'];
        ChatMember::firstOrCreate(['user_id' => $user_id, 'chat_id' => $chat->id]);
        return $this->onSuccess(null, 'Chat member added successfully.', 201);
    }
    public function removeMember(Request $request, Chat $chat, User $user) : JsonResponse
    {
        $chatMember = ChatMember::firstWhere([
            ['user_id' => $user->id],
            ['chat_id' => $chat->id]
        ]);
        $this->authorize('delete', [ChatMember::class, $request->user(), $chatMember]);

        $chatMember->delete();
        return $this->onSuccess(null, 'Chat member removed successfully.');
    }
}
