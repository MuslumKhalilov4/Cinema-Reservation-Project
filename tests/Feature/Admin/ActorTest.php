<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Database\Seeders\RolePermissionSeeder;
use App\Models\{User, Actor};
use Laravel\Sanctum\Sanctum;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ActorTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    public function test_admin_can_get_actors()
    {
        $admin = User::factory()->create();
        Actor::factory()->count(3)->create();
        $admin->assignRole('admin');

        Sanctum::actingAs($admin);

        $response = $this->getJson('/api/admin/actors');

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'success',
            'message',
            'data' => [
                '*' => $this->actorJsonStructure(),
            ]
        ]);
    }

    public function test_admin_can_create_actor()
    {
        $storage = Storage::fake('public');
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        Sanctum::actingAs($admin);

        $data = [
            'first_name' => 'Test',
            'last_name' => 'Test',
            'biography' => [
                'az' => 'Test Biography',
                'en' => 'Test Biography',
                'ru' => 'Test Biography',
            ],
            'birth_date' => '2000-01-01',
            'nationality' => 'Test Nationality',
            'place_of_birth' => 'Test Place of Birth',
            'height' => 180,
            'picture' => UploadedFile::fake()->image('test.jpg'),
        ];

        $response = $this->postJson('/api/admin/actors', $data);

        $response->assertStatus(201);

        $response->assertJsonStructure([
            'success',
            'message',
            'data' => $this->actorJsonStructure(),
        ]);

        $storage->assertExists('actors/' . $data['picture']->hashName());
    }

    public function test_admin_can_show_actor()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        Sanctum::actingAs($admin);

        $actor = Actor::factory()->create();

        $response = $this->getJson('/api/admin/actors/' . $actor->id);

        $response->assertStatus(200);

        $response->assertJsonStructure();
    }

    public function test_admin_can_update_actor()
    {
        $storage = Storage::fake('public');
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        Sanctum::actingAs($admin);

        $actor = Actor::factory()->create();

        $data = [
            'first_name' => 'Updated Test',
            'last_name' => 'Updated Test',
            'biography' => [
                'az' => 'Updated Test Biography',
                'en' => 'Updated Test Biography',
                'ru' => 'Updated Test Biography',
            ],
            'birth_date' => '2000-01-01',
            'nationality' => 'Updated Test Nationality',
            'place_of_birth' => 'Updated Test Place of Birth',
            'height' => 180,
            'picture' => UploadedFile::fake()->image('test.jpg'),
        ];

        $response = $this->putJson('/api/admin/actors/' . $actor->id, $data);

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'success',
            'message',
            'data' => $this->actorJsonStructure(),
        ]);

        $storage->assertExists('actors/' . $data['picture']->hashName());

        $this->assertDatabaseHas('actors', [
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'biography->en' => $data['biography']['en'],
            'biography->az' => $data['biography']['az'],
            'biography->ru' => $data['biography']['ru'],
            'birth_date' => $data['birth_date'],
            'nationality' => $data['nationality'],
            'place_of_birth' => $data['place_of_birth'],
            'height' => $data['height'],
            'picture_url' => 'actors/' . $data['picture']->hashName(),
        ]);
    }

    public function test_admin_can_delete_actor()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        Sanctum::actingAs($admin);

        $actor = Actor::factory()->create();

        $response = $this->deleteJson('/api/admin/actors/' . $actor->id);

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'success',
            'message',
        ]);

        $this->assertDatabaseMissing('actors', [
            'id' => $actor->id,
        ]);
    }

    public function actorJsonStructure(): array
    {
        return [    
                'full_name',
                'biography' => ['az', 'en', 'ru'],
                'birth_date',
                'nationality',
                'place_of_birth',
                'height',
                'picture_url',
                'created_at',
                'updated_at'
        ];
    }
}
