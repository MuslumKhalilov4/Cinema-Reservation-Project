<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Database\Seeders\RolePermissionSeeder;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Illuminate\Support\Str;
use App\Models\Genre;

class GenreTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    public function test_admin_can_get_genres()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        Genre::factory()->count(2)->create();

        Sanctum::actingAs($admin);

        $response = $this->getJson('/api/admin/genres');

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'success',
            'message',
            'data' => [
                '*' => [
                    'name' => ['az', 'en', 'ru'],
                    'slug',
                    'created_at',
                    'updated_at'
                ]
            ]
        ]);
    }

    public function test_admin_can_create_genre()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        Sanctum::actingAs($admin);

        $data = [
            'name' => [
                'az' => 'Test Genre',
                'en' => 'Test Genre',
                'ru' => 'Test Genre',
            ],
        ];

        $response = $this->postJson('/api/admin/genres', $data);

        $response->assertStatus(201);

        $response->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'name' => ['az', 'en', 'ru'],
                'slug',
                'created_at',
                'updated_at'
            ]
        ]);

        $this->assertDatabaseHas('genres', [
            'name->en' => $data['name']['en'],
            'slug' => Str::slug($data['name']['en']),
        ]);
    }

    public function test_admin_can_show_genre()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        Sanctum::actingAs($admin);

        $genre = Genre::factory()->create();

        $response = $this->getJson('/api/admin/genres/' . $genre->id);

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'name' => ['az', 'en', 'ru'],
                'slug',
                'created_at',
                'updated_at'
            ]
        ]);
    }

    public function test_admin_can_update_genre()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        Sanctum::actingAs($admin);

        $genre = Genre::factory()->create();

        $data = [
            'name' => [
                'az' => 'Updated Genre',
                'en' => 'Updated Genre',
                'ru' => 'Updated Genre',
            ],
        ];

        $response = $this->putJson('/api/admin/genres/' . $genre->id, $data);

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'name' => ['az', 'en', 'ru'],
                'slug',
                'created_at',
                'updated_at'
            ]
        ]);

        $this->assertDatabaseHas('genres', [
            'id' => $genre->id,
            'name->en' => $data['name']['en'],
            'slug' => Str::slug($data['name']['en']),
        ]);
    }

    public function test_admin_can_delete_genre()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        Sanctum::actingAs($admin);

        $genre = Genre::factory()->create();

        $response = $this->deleteJson('/api/admin/genres/' . $genre->id);

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'success',
            'message',
        ]);

        $this->assertDatabaseMissing('genres', [
            'id' => $genre->id,
        ]);
    }
}
