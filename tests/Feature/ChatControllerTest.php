<?php

namespace Tests\Feature;

use App\Enums\Role;
use App\Models\Chat;
use App\Models\ChatMember;
use App\Models\Message;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChatControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create(['role' => Role::ADMIN]);
    }

    /** @test */
    public function it_returns_user_chats_on_index()
    {
        $otherUser = User::factory()->create();
        $chat1 = Chat::factory()->create();
        $chat2 = Chat::factory()->create();
        $chat3 = Chat::factory()->create(); // чат без пользователя

        ChatMember::factory()->create(['chat_id' => $chat1->id, 'user_id' => $this->user->id]);
        ChatMember::factory()->create(['chat_id' => $chat2->id, 'user_id' => $this->user->id]);
        ChatMember::factory()->create(['chat_id' => $chat1->id, 'user_id' => $otherUser->id]);

        $response = $this->actingAs($this->user)->getJson('/api/chat');

        $response->assertOk()
            ->assertJsonPath('data.0.id', $chat1->id)
            ->assertJsonPath('data.1.id', $chat2->id)
            ->assertJsonCount(2, 'data');
    }

    /** @test */
    public function index_requires_authentication()
    {
        $this->getJson('/api/chat')->assertUnauthorized();
    }

    /** @test */
    public function it_creates_a_chat()
    {
        $payload = [
            'name' => 'New Chat',
            'is_private_chat' => true,
        ];

        $response = $this->actingAs($this->user)->postJson('/api/chat', $payload);

        $response->assertCreated()
            ->assertJsonPath('data.name', 'New Chat')
            ->assertJsonPath('data.is_private_chat', true);

        $this->assertDatabaseHas('chats', ['name' => 'New Chat']);
        $this->assertDatabaseHas('chat_members', [
            'chat_id' => $response->json('data.id'),
            'user_id' => $this->user->id,
        ]);
    }

    /** @test */
    public function store_validates_required_name()
    {
        $response = $this->actingAs($this->user)->postJson('/api/chat', []);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['name']);
    }

    /** @test */
    public function store_validates_name_min_length()
    {
        $response = $this->actingAs($this->user)->postJson('/api/chat', ['name' => 'ab']);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['name']);
    }

    /** @test */
    public function it_shows_a_chat()
    {
        $chat = Chat::factory()->create();
        ChatMember::factory()->create(['chat_id' => $chat->id, 'user_id' => $this->user->id]);

        $response = $this->actingAs($this->user)->getJson("/api/chat/{$chat->id}");

        $response->assertOk()
            ->assertJsonPath('data.id', $chat->id)
            ->assertJsonPath('data.name', $chat->name);
    }

    /** @test */
    public function show_returns_404_for_missing_chat()
    {
        $this->actingAs($this->user)
            ->getJson('/api/chat/999')
            ->assertNotFound();
    }

    /** @test */
    public function it_updates_a_chat()
    {
        $chat = Chat::factory()->create(['name' => 'Old Name']);
        ChatMember::factory()->create(['chat_id' => $chat->id, 'user_id' => $this->user->id]);

        $response = $this->actingAs($this->user)
            ->putJson("/api/chat/{$chat->id}", ['name' => 'Updated Name']);

        $response->assertOk()
            ->assertJsonPath('data.name', 'Updated Name');

        $this->assertDatabaseHas('chats', ['id' => $chat->id, 'name' => 'Updated Name']);
    }

    /** @test */
    public function update_validates_name_required()
    {
        $chat = Chat::factory()->create();
        ChatMember::factory()->create(['chat_id' => $chat->id, 'user_id' => $this->user->id]);

        $response = $this->actingAs($this->user)
            ->putJson("/api/chat/{$chat->id}", ['name' => '']);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['name']);
    }

    /** @test */
    public function it_deletes_a_chat()
    {
        $chat = Chat::factory()->create();
        ChatMember::factory()->create(['chat_id' => $chat->id, 'user_id' => $this->user->id]);

        $response = $this->actingAs($this->user)
            ->deleteJson("/api/chat/{$chat->id}");

        $response->assertOk()
            ->assertJsonPath('message', 'Chat deleted successfully.');

        $this->assertDatabaseMissing($chat->getTable(), [$chat->id]);
    }

    /** @test */
    public function destroy_returns_404_for_missing_chat()
    {
        $this->actingAs($this->user)
            ->deleteJson('/api/chat/999')
            ->assertNotFound();
    }

    /** @test */
    public function it_returns_chat_members()
    {
        $chat = Chat::factory()->create();
        $member1 = ChatMember::factory()->create(['chat_id' => $chat->id]);
        $member2 = ChatMember::factory()->create(['chat_id' => $chat->id]);

        $response = $this->actingAs($this->user)
            ->getJson("/api/chat/{$chat->id}/members");

        $response->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonPath('data.0.id', $member1->user_id)
            ->assertJsonPath('data.1.id', $member2->user_id);
    }

    /** @test */
    public function it_adds_a_member_to_chat()
    {
        $chat = Chat::factory()->create();
        ChatMember::factory()->create(['chat_id' => $chat->id, 'user_id' => $this->user->id]);
        $newMember = User::factory()->create();

        $response = $this->actingAs($this->user)
            ->postJson("/api/chat/{$chat->id}/members", ['user_id' => $newMember->id]);

        $response->assertCreated()
            ->assertJsonPath('data.id', $newMember->id);

        $this->assertDatabaseHas('chat_members', [
            'chat_id' => $chat->id,
            'user_id' => $newMember->id,
        ]);
    }

    /** @test */
    public function adding_an_existing_member_returns_the_member_without_duplicate()
    {
        $chat = Chat::factory()->create();
        ChatMember::factory()->create(['chat_id' => $chat->id, 'user_id' => $this->user->id]);
        $existing = User::factory()->create();
        ChatMember::factory()->create(['chat_id' => $chat->id, 'user_id' => $existing->id]);

        $response = $this->actingAs($this->user)
            ->postJson("/api/chat/{$chat->id}/members", ['user_id' => $existing->id]);

        $response->assertCreated()
            ->assertJsonPath('data.id', $existing->id);

        $this->assertDatabaseCount('chat_members', 2);
    }

    /** @test */
    public function add_member_validates_user_id_required_and_exists()
    {
        $chat = Chat::factory()->create();

        // без user_id
        $this->actingAs($this->user)
            ->postJson("/api/chat/{$chat->id}/members", [])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['user_id']);

        // несуществующий user_id
        $this->actingAs($this->user)
            ->postJson("/api/chat/{$chat->id}/members", ['user_id' => 9999])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['user_id']);
    }

    /** @test */
    public function remove_member_returns_404_if_member_not_in_chat()
    {
        $chat = Chat::factory()->create();
        $nonMember = User::factory()->create();

        $response = $this->actingAs($this->user)
            ->deleteJson("/api/chat/{$chat->id}/members/{$nonMember->id}");

        $response->assertNotFound()
            ->assertJsonPath('message', 'Content not found.');
    }

    /** @test */
    public function it_returns_messages_of_a_chat_for_a_member()
    {
        $chat = Chat::factory()->create();
        ChatMember::factory()->create(['chat_id' => $chat->id, 'user_id' => $this->user->id]);
        $message1 = Message::factory()->create(['chat_id' => $chat->id, 'sender_id' => $this->user->id]);
        $message2 = Message::factory()->create(['chat_id' => $chat->id, 'sender_id' => $this->user->id]);

        $response = $this->actingAs($this->user)
            ->getJson("/api/chat/{$chat->id}/messages");

        $response->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonPath('data.0.id', $message1->id)
            ->assertJsonPath('data.1.id', $message2->id);
    }

    /** @test */
    public function get_messages_denied_if_user_is_not_member_of_chat()
    {
        $chat = Chat::factory()->create();
        Message::factory()->create(['chat_id' => $chat->id]);

        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->getJson("/api/chat/$chat->id/messages");

        $response->assertJsonPath('status', 403);
        $response->assertJsonPath('message', 'You do not have permission to view this chat.');
    }

    /** @test */
    public function it_sends_a_message_to_chat()
    {
        $chat = Chat::factory()->create();
        ChatMember::factory()->create(['chat_id' => $chat->id, 'user_id' => $this->user->id]);

        $payload = ['content' => 'Hello, world!'];

        $response = $this->actingAs($this->user)
            ->postJson("/api/chat/{$chat->id}/messages", $payload);

        $response->assertCreated()
            ->assertJsonPath('data.content', 'Hello, world!')
            ->assertJsonPath('data.relationships.sender.id', $this->user->id);

        $this->assertDatabaseHas('messages', [
            'chat_id' => $chat->id,
            'sender_id' => $this->user->id,
            'content' => 'Hello, world!',
        ]);
    }

    /** @test */
    public function send_message_validates_content_required()
    {
        $chat = Chat::factory()->create();
        ChatMember::factory()->create(['chat_id' => $chat->id, 'user_id' => $this->user->id]);

        $response = $this->actingAs($this->user)
            ->postJson("/api/chat/{$chat->id}/messages", []);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['content']);
    }

    /** @test */
    public function send_message_validates_content_max_length()
    {
        $chat = Chat::factory()->create();
        ChatMember::factory()->create(['chat_id' => $chat->id, 'user_id' => $this->user->id]);

        $response = $this->actingAs($this->user)
            ->postJson("/api/chat/{$chat->id}/messages", ['content' => str_repeat('a', 256)]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['content']);
    }
}
