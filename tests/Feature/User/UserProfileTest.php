<?php

namespace Tests\Feature\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Illuminate\Support\Facades\Notification;
use Illuminate\Auth\Notifications\VerifyEmail;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Support\Facades\Hash;
use App\Constants\ResponseMessage;
use App\Exceptions\UserException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class UserProfileTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    public function test_user_can_get_profile()
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $response = $this->getJson('/api/profile');

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'success',
            'message',
            'data' => ['user' => [
                'id',
                'first_name',
                'last_name',
                'username',
                'email',
                'phone',
                'avatar_url',
            ]],
        ]);
    }

    public function test_user_can_update_profile()
    {
        $user = User::factory()->create();
        $originalFirstName = $user->first_name;

        Sanctum::actingAs($user);

        $response = $this->patchJson('/api/profile/update', [
            'first_name' => 'Test',
            'last_name' => 'Test',
            'username' => 'test_user',
            'email' => 'test@example.com',
            'phone' => '+994500000000',
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('users', [
            'first_name' => 'Test',
            'last_name' => 'Test',
            'username' => 'test_user',
            'email' => 'test@example.com',
            'phone' => '+994500000000',
        ]);

        $this->assertDatabaseHas('activity_log', [
            'log_name' => 'auth',
            'event' => 'update_profile',
            'description' => 'User updated profile. Username: test_user',
            'properties->old_data->first_name' => $originalFirstName,
            'properties->changes->first_name' => 'Test',
        ]);
    }

    public function test_when_email_is_changed_it_gets_unverified_and_verification_email_is_sent()
    {
        Notification::fake();

        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $response = $this->patchJson('/api/profile/update', [
            'email' => 'test@example.com',
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'email_verified_at' => null,
        ]);

        Notification::assertSentTo($user, VerifyEmail::class);
    }

    public function test_user_can_change_password()
    {
        $user = User::factory()->create(['password' => Hash::make('test_password')]);

        Sanctum::actingAs($user);

        $response = $this->putJson('/api/profile/change-password', [
            'old_password' => 'test_password',
            'new_password' => 'new_password123!',
            'new_password_confirmation' => 'new_password123!',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => ResponseMessage::PASSWORD_CHANGED_SUCCESSFULLY,
        ]);

        $this->assertDatabaseHas('activity_log', [
            'log_name' => 'auth',
            'event' => 'change_password',
            'description' => 'User changed password. Username: ' . $user->username,
        ]);
        
        
    }

    public function test_user_cannot_change_password_with_incorrect_old_password()
    {
        $user = User::factory()->create(['password' => Hash::make('test_password')]);

        Sanctum::actingAs($user);

        $response = $this->putJson('/api/profile/change-password', [
            'old_password' => 'incorrect_password',
            'new_password' => 'new_password123!',
            'new_password_confirmation' => 'new_password123!',
        ]);

        $response->assertStatus(400);
        $response->assertJson([
            'success' => false,
            'message' => UserException::oldPasswordIncorrect()->getMessage(),
        ]);
    }

    public function test_user_can_change_avatar()
    {
        $storage = Storage::fake('public');

        $avatar = UploadedFile::fake()->image('avatar.jpg');

        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $response = $this->putJson('/api/profile/change-avatar', [
            'avatar' => $avatar,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => ResponseMessage::AVATAR_CHANGED_SUCCESSFULLY,
        ]);

        $this->assertDatabaseHas('users', [
            'avatar_url' => 'avatars/' . $avatar->hashName(),
        ]);

        $storage->assertExists('avatars/' . $avatar->hashName());
    }

    public function test_user_can_delete_account()
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $response = $this->deleteJson('/api/profile/delete');

        $response->assertStatus(200);

        $response->assertJson([
            'success' => true,
            'message' => ResponseMessage::ACCOUNT_DELETED_SUCCESSFULLY,
        ]);

        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
        ]);

        $this->assertDatabaseHas('activity_log', [
            'log_name' => 'auth',
            'event' => 'delete_account',
            'description' => 'User deleted account. Username: ' . $user->username,
        ]);
    }
}
