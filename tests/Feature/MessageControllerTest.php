<?php

namespace Tests\Feature;

use App\Models\Chat;
use App\Models\Message;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MessageControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Message $message;
    private User $otherUser;
    private Chat $chat;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create(['role' => 'admin']);
        $this->otherUser = User::factory()->create();

        $this->chat = Chat::factory()->create();

        // Сообщение от текущего пользователя
        $this->message = Message::factory()->create([
            'chat_id' => $this->chat->id,
            'sender_id' => $this->user->id,
            'content' => 'Original content',
        ]);
    }

    /** @test */
    public function it_shows_a_message()
    {
        $response = $this->actingAs($this->user)
            ->getJson("/api/message/{$this->message->id}");

        $response->assertOk()
            ->assertJsonPath('data.id', $this->message->id)
            ->assertJsonPath('data.content', 'Original content');
    }

    /** @test */
    public function show_returns_404_for_missing_message()
    {
        $this->actingAs($this->user)
            ->getJson('/api/message/999')
            ->assertNotFound();
    }

    /** @test */
    public function show_requires_authentication()
    {
        $this->getJson("/api/message/{$this->message->id}")
            ->assertUnauthorized();
    }

    /** @test */
    public function it_updates_a_message_by_owner()
    {
        $content = 'Updated message content';
        $response = $this->actingAs($this->user)
            ->putJson("/api/message/{$this->message->id}", [
                'content' => $content,
            ]);

        //throw new \Exception(json_encode($response));
        $response->assertOk()
            ->assertJsonPath('data.content', $content);

        //dd($response);

        $this->assertDatabaseHas('messages', [
            'id' => $this->message->id,
            'content' => $content,
        ]);
    }

    /** @test */
    public function update_fails_if_user_is_not_sender()
    {
        // Попытка обновления сообщения, созданного другим пользователем
        $response = $this->actingAs($this->otherUser)
            ->putJson("/api/message/{$this->message->id}", [
                'content' => 'Hacked content',
            ]);

        // В FormRequest authorize() проверяет sender_id === user()->id, возвращает 403 при провале
        $response->assertJsonPath('status', 403);
    }

    /** @test */
    public function update_validates_content_required()
    {
        $response = $this->actingAs($this->user)
            ->putJson("/api/message/{$this->message->id}", []);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['content']);
    }

    /** @test */
    public function update_validates_content_max_length()
    {
        $response = $this->actingAs($this->user)
            ->putJson("/api/message/{$this->message->id}", [
                'content' => str_repeat('a', 301),
            ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['content']);
    }

    /** @test */
    public function update_requires_authentication()
    {
        $this->putJson("/api/message/{$this->message->id}", [
            'content' => 'Updated',
        ])
            ->assertUnauthorized();
    }

    /** @test */
    public function it_deletes_a_message()
    {
        $message = Message::factory()->create(['chat_id' => $this->chat->id, 'sender_id' => $this->user->id, 'content' => 'some content']);
        $response = $this->actingAs($this->user)
            ->deleteJson("/api/message/$message->id");

        $response->assertOk()
            ->assertJsonPath('message', 'Message deleted successfully.');

        $this->assertSoftDeleted($message->getTable(), ['id' => $message->id]); // если используется SoftDeletes; иначе assertDatabaseMissing
    }

    /** @test */
    public function destroy_returns_404_for_missing_message()
    {
        $this->actingAs($this->user)
            ->deleteJson('/api/message/999')
            ->assertNotFound();
    }

    /** @test */
    public function destroy_requires_authentication()
    {
        $this->deleteJson("/api/message/{$this->message->id}")
            ->assertUnauthorized();
    }
}
