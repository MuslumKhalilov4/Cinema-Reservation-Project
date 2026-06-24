<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Database\Seeders\RolePermissionSeeder;
use App\Models\{User, SeatCategory};
use Laravel\Sanctum\Sanctum;

class SeatCategoryTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    public function test_admin_can_get_seat_categories()
    {
        $admin = User::factory()->create();
        SeatCategory::factory()->count(3)->create();
        $admin->assignRole('admin');

        Sanctum::actingAs($admin);

        $response = $this->getJson('/api/admin/seat-categories');

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'success',
            'message',
            'data' => [
                '*' => $this->seatCategoryJsonStructure(),
            ],
        ]);
    }

    public function test_admin_can_create_seat_category()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        Sanctum::actingAs($admin);

        $data = [
            'name' => 'VIP',
            'additional_price' => 5.50,
            'color_code' => '#FF5733',
        ];

        $response = $this->postJson('/api/admin/seat-categories', $data);

        $response->assertStatus(201);

        $response->assertJsonStructure([
            'success',
            'message',
            'data' => $this->seatCategoryJsonStructure(),
        ]);

        $this->assertDatabaseHas('seat_categories', [
            'name' => $data['name'],
            'additional_price' => $data['additional_price'],
            'color_code' => $data['color_code'],
        ]);
    }

    public function test_admin_can_show_seat_category()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        Sanctum::actingAs($admin);

        $seatCategory = SeatCategory::factory()->create();

        $response = $this->getJson('/api/admin/seat-categories/' . $seatCategory->id);

        $response->assertStatus(200);

        $response->assertJsonStructure();
    }

    public function test_admin_can_update_seat_category()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        Sanctum::actingAs($admin);

        $seatCategory = SeatCategory::factory()->create();

        $data = [
            'name' => 'Updated VIP',
            'additional_price' => 7.00,
            'color_code' => '#33FF57',
        ];

        $response = $this->putJson('/api/admin/seat-categories/' . $seatCategory->id, $data);

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'success',
            'message',
            'data' => $this->seatCategoryJsonStructure(),
        ]);

        $this->assertDatabaseHas('seat_categories', [
            'id' => $seatCategory->id,
            'name' => $data['name'],
            'additional_price' => $data['additional_price'],
            'color_code' => $data['color_code'],
        ]);
    }

    public function test_admin_can_delete_seat_category()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        Sanctum::actingAs($admin);

        $seatCategory = SeatCategory::factory()->create();

        $response = $this->deleteJson('/api/admin/seat-categories/' . $seatCategory->id);

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'success',
            'message',
        ]);

        $this->assertDatabaseMissing('seat_categories', [
            'id' => $seatCategory->id,
        ]);
    }

    public function seatCategoryJsonStructure(): array
    {
        return [
            'name',
            'additional_price',
            'color_code',
            'created_at',
            'updated_at',
        ];
    }
}
