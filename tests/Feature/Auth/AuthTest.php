<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Exceptions\AuthException;
use App\Constants\ResponseMessage;
use Database\Seeders\RolePermissionSeeder;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    public function test_user_can_register_successfully()
    {
        $storage = Storage::fake('public');

        $avatar = UploadedFile::fake()->image('avatar.jpg');

        $data = [
            'first_name' => 'Test',
            'last_name' => 'Test',
            'username' => 'test_user',
            'email' => 'test@example.com',
            'phone' => '1234567890',
            'password' => 'password123!',
            'password_confirmation' => 'password123!',
            'avatar' => $avatar,
        ];

        $response = $this->postJson('/api/register', $data);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'success',
            'message',
            'data' => ['user','token'],
        ]);

        $this->assertDatabaseHas('users', [
            'username' => 'test_user',
            'email' => 'test@example.com',
        ]);

        $storage->assertExists('avatars/' . $avatar->hashName());

        $this->assertDatabaseHas('activity_log', [
            'log_name' => 'auth',
            'event' => 'register',
            'description' => 'New user registered. Username: ' . $data['username'],

            'properties->email' => $data['email'],
            'properties->phone' => $data['phone'],
            'properties->username' => $data['username'],
        ]);
    }

    public function test_user_can_login_successfully(){
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'username' => 'test_user',
            'password' => Hash::make('password'),
        ]);

        $data = [
            'email' => $user->email,
            'password' => 'password',
        ];

        $response = $this->postJson('/api/login', $data);
        
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'message',
            'data' => ['user','token'],
        ]);

        $this->assertDatabaseHas('activity_log', [
            'log_name' => 'auth',
            'event' => 'login',
            'description' => 'User logged in. Username: ' . $user->username,
            'subject_id' => $user->id,
            'subject_type' => User::class,
            'causer_id' => $user->id,
            'causer_type' => User::class,
        ]);
    }

    public function test_user_cannot_login_with_invalid_credentials(){
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
        ]);

        $data = [
            'email' => $user->email,
            'password' => 'wrong_password',
        ];

        $response = $this->postJson('/api/login', $data);
        
        $response->assertStatus(AuthException::invalidCredentials()->getCode());
        $response->assertJson([
            'success' => false,
            'message' => AuthException::invalidCredentials()->getMessage(),
        ]);
    }
    public function test_user_can_logout_successfully(){
        $user = User::factory()->create()->assignRole('user');

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->postJson('/api/logout');

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => ResponseMessage::USER_LOGOUT_SUCCESSFULLY,
        ]);

        $this->assertDatabaseMissing('personal_access_tokens', [
            'tokenable_id' => $user->id,
            'tokenable_type' => User::class,
        ]);

        $this->assertDatabaseHas('activity_log', [
            'log_name' => 'auth',
            'event' => 'logout',
            'description' => 'User logged out. Username: ' . $user->username,
            'subject_id' => $user->id,
            'subject_type' => User::class,
            'causer_id' => $user->id,
            'causer_type' => User::class,
        ]);
    }
}
