<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Database\Seeders\RolePermissionSeeder;
use App\Models\{User, Cinema, Hall};
use Laravel\Sanctum\Sanctum;
use Illuminate\Support\Str;

class HallTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    public function test_admin_can_get_halls()
    {
        $admin = User::factory()->create();
        $cinema = Cinema::factory()->create();
        Hall::factory()->count(3)->create(['cinema_id' => $cinema->id]);
        $admin->assignRole('admin');

        Sanctum::actingAs($admin);

        $response = $this->getJson('/api/admin/halls');

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'success',
            'message',
            'data' => [
                '*' => $this->hallJsonStructure(),
            ],
        ]);
    }

    public function test_admin_can_create_hall()
    {
        $admin = User::factory()->create();
        $cinema = Cinema::factory()->create();
        $admin->assignRole('admin');

        Sanctum::actingAs($admin);

        $data = [
            'name' => 'Test Hall',
            'cinema_id' => $cinema->id,
        ];

        $response = $this->postJson('/api/admin/halls', $data);

        $response->assertStatus(201);

        $response->assertJsonStructure([
            'success',
            'message',
            'data' => $this->hallJsonStructure(),
        ]);

        $this->assertDatabaseHas('halls', [
            'name' => $data['name'],
            'slug' => Str::slug($data['name']),
            'cinema_id' => $data['cinema_id'],
        ]);
    }

    public function test_admin_can_show_hall()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        Sanctum::actingAs($admin);

        $cinema = Cinema::factory()->create();
        $hall = Hall::factory()->create(['cinema_id' => $cinema->id]);

        $response = $this->getJson('/api/admin/halls/' . $hall->id);

        $response->assertStatus(200);

        $response->assertJsonStructure();
    }

    public function test_admin_can_update_hall()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        Sanctum::actingAs($admin);

        $cinema = Cinema::factory()->create();
        $hall = Hall::factory()->create(['cinema_id' => $cinema->id]);

        $data = [
            'name' => 'Updated Hall',
        ];

        $response = $this->putJson('/api/admin/halls/' . $hall->id, $data);

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'success',
            'message',
            'data' => $this->hallJsonStructure(),
        ]);

        $this->assertDatabaseHas('halls', [
            'id' => $hall->id,
            'name' => $data['name'],
            'slug' => Str::slug($data['name']),
        ]);
    }

    public function test_admin_can_delete_hall()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        Sanctum::actingAs($admin);

        $cinema = Cinema::factory()->create();
        $hall = Hall::factory()->create(['cinema_id' => $cinema->id]);

        $response = $this->deleteJson('/api/admin/halls/' . $hall->id);

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'success',
            'message',
        ]);

        $this->assertDatabaseMissing('halls', [
            'id' => $hall->id,
        ]);
    }

    public function hallJsonStructure(): array
    {
        return [
            'name',
            'slug',
            'capacity',
            'row_count',
            'cinema_id',
            'created_at',
            'updated_at',
        ];
    }
}
