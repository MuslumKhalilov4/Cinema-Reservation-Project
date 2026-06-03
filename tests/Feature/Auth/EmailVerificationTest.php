<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\URL;
use App\Constants\ResponseMessage;
use Illuminate\Support\Facades\Route;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Route::middleware(['auth:sanctum', 'verified'])
            ->get('/api/test-route', function () {
                return response()->json([
                    'message' => 'Test passed',
                ]);
            });
    }

    public function test_user_can_verify_email_successfully(){
        $user = User::factory()->unverified()->create();

        $url = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->getEmailForVerification())]
        );

        $response = $this->getJson($url);

        $response->assertStatus(200);
        $response->assertJson([
            'message' => ResponseMessage::EMAIL_VERIFICATION_SUCCESSFUL,
        ]);

        $this->assertTrue($user->fresh()->hasVerifiedEmail());
    }

    public function test_user_cannot_verify_email_with_invalid_signature(){
        $user = User::factory()->unverified()->create();

        $url = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->getEmailForVerification())]
        );

        $invalidUrl = $url . 'invalid_signature';

        $response = $this->getJson($invalidUrl);

        $response->assertStatus(403);

        $this->assertFalse($user->fresh()->hasVerifiedEmail());
    }

    public function test_user_cannot_verify_email_with_already_verified_email(){
        $user = User::factory()->create();

        $url = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->getEmailForVerification())]
        );

        $response = $this->getJson($url);

        $response->assertStatus(400);
        $response->assertJson([
            'message' => ResponseMessage::EMAIL_VERIFICATION_ALREADY_VERIFIED,
        ]);

        $this->assertTrue($user->fresh()->hasVerifiedEmail());
    }

    public function test_user_cannot_access_verified_route_without_verified_email(){
        $user = User::factory()->unverified()->create();

        $response = $this->actingAs($user, 'sanctum')->getJson('/api/test-route');

        $response->assertStatus(403);
        $response->assertJson([
            'message' => 'Your email address is not verified.',
        ]);
    }
}
