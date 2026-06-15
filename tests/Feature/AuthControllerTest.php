<?php

namespace Tests\Feature;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Регистрация нового пользователя администратором.
     */
    public function test_register_creates_user_and_returns_token(): void
    {
        // Создаём администратора и аутентифицируем его
        $admin = User::factory()->create([
            'role' => Role::ADMIN
            ]);
        Sanctum::actingAs($admin);

        $payload = [
            'login'                 => 'new_user',
            'first_name'            => 'John',
            'last_name'             => 'Doe',
            'patronymic'            => 'Ivanovich',   // необязательное поле
            'email'                 => 'john@example.com',
            'phone' => '88005553535',
            'password'              => 'password',
            'password_confirmation' => 'password',
        ];

        $response = $this->postJson(route('api.register'), $payload);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'user' => [
                    'id',
                    'login',
                    'first_name',
                    'last_name',
                    'email',
                ],
                'token',
            ]);

        $this->assertDatabaseHas('users', [
            'login' => 'new_user',
            'email' => 'john@example.com',
            'first_name' => 'John',
            'last_name' => 'Doe',
        ]);
    }

    /**
     * Ошибки валидации при регистрации (от имени администратора).
     */
    public function test_register_fails_with_invalid_data(): void
    {
        $admin = User::factory()->create(['role' => Role::ADMIN]);

        Sanctum::actingAs($admin);

        $payload = [
            'login'    => '',           // отсутствует
            'email'    => 'invalid',    // невалидный email
            'password' => 'short',      // без подтверждения, слишком короткий (если правило min)
        ];

        $response = $this->postJson(route('api.register'), $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'login',
                'first_name',
                'last_name',
                'email',
                'password',
            ]);
    }

    /**
     * Успешный вход с правильными учётными данными.
     */
    public function test_login_returns_token_for_valid_credentials(): void
    {
        User::factory()->create([
            'login'    => 'john_doe',
            'email'    => 'john@example.com',
            'password' => bcrypt('secret123'),
        ]);

        $payload = [
            'login'    => 'john_doe',
            'password' => 'secret123',
        ];

        $response = $this->postJson(route('api.login'), $payload);

        $response->assertOk()
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'user',
                    'token',
                ],
            ])
            ->assertJsonPath('message', 'User logged in successfully');
    }

    /**
     * Вход с неверным паролем.
     */
    public function test_login_fails_with_wrong_password(): void
    {
        User::factory()->create([
            'login'    => 'john_doe',
            'password' => bcrypt('secret123'),
        ]);

        $payload = [
            'login'    => 'john_doe',
            'password' => 'wrong-password',
        ];

        $response = $this->postJson(route('api.login'), $payload);

        $response->assertStatus(401)
            ->assertJson([
                'status'  => 401,
                'message' => 'Invalid credentials',
            ]);
    }

    /**
     * Вход для несуществующего пользователя.
     */
    public function test_login_fails_for_nonexistent_user(): void
    {
        $payload = [
            'login'    => 'ghost',
            'password' => 'any-password',
        ];

        $response = $this->postJson(route('api.login'), $payload);

        $response->assertStatus(401)
            ->assertJson([
                'status'  => 401,
                'message' => 'Invalid credentials',
            ]);
    }

    /**
     * Выход авторизованного пользователя.
     */
    public function test_logout_deletes_current_token(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->postJson(route('api.logout'));

        $response->assertOk()
            ->assertJson([
                'status'  => 200,
                'message' => 'Logged out successfully',
                'data'    => null,
            ]);

        $this->assertDatabaseMissing('personal_access_tokens', [
            'tokenable_id' => $user->id,
            'name'         => 'test-token',
        ]);
    }

    /**
     * Попытка выхода без аутентификации.
     */
    public function test_logout_fails_when_unauthenticated(): void
    {
        $response = $this->postJson(route('api.logout'));

        $response->assertStatus(401);
    }

    /**
     * Получение информации о текущем пользователе.
     */
    public function test_user_endpoint_returns_authenticated_user(): void
    {
        $user = User::factory()->create([
            'login' => 'john_doe',
            'first_name'  => 'Jane',
            'last_name' => 'Doe',
            'email' => 'jane@example.com',
        ]);

        Sanctum::actingAs($user);

        $response = $this->getJson(route('api.user'));

        $response->assertOk()
            ->assertJson([
                'status'  => 200,
                'message' => 'User retrieved successfully',
                'data'    => [
                    'id'    => $user->id,
                    'login' => $user->login,
                    'first_name'  => 'Jane',
                    'last_name'  => 'Doe',
                    'email' => 'jane@example.com',
                ],
            ]);
    }

    /**
     * Запрос /user без аутентификации.
     */
    public function test_user_endpoint_fails_when_unauthenticated(): void
    {
        $response = $this->getJson(route('api.user'));

        $response->assertStatus(401);
    }

    /**
     * Проверка статуса авторизации для авторизованного пользователя.
     */
    public function test_check_endpoint_for_authenticated_user(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->getJson(route('api.Auth.Check'));

        $response->assertOk()
            ->assertJson([
                'status'  => 200,
                'message' => 'You are logged in',
            ]);
    }

    /**
     * Проверка статуса авторизации для неавторизованного пользователя.
     */
    public function test_check_endpoint_fails_when_unauthenticated(): void
    {
        $response = $this->getJson(route('api.Auth.Check'));

        $response->assertStatus(401);
    }
}
