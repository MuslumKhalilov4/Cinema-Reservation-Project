<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Database\Seeders\RolePermissionSeeder;
use App\Models\{User, Cinema, Hall, SeatCategory, Seat};
use Laravel\Sanctum\Sanctum;

class SeatTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    public function test_admin_can_get_seats()
    {
        $admin = User::factory()->create();
        $cinema = Cinema::factory()->create();
        $hall = Hall::factory()->create(['cinema_id' => $cinema->id]);
        $seatCategory = SeatCategory::factory()->create();

        $this->createSeat(['hall_id' => $hall->id, 'seat_category_id' => $seatCategory->id]);
        $this->createSeat(['hall_id' => $hall->id, 'seat_category_id' => $seatCategory->id, 'row' => 'B', 'seat_number' => 2]);
        $this->createSeat(['hall_id' => $hall->id, 'seat_category_id' => $seatCategory->id, 'row' => 'C', 'seat_number' => 3]);
        $admin->assignRole('admin');

        Sanctum::actingAs($admin);

        $response = $this->getJson('/api/admin/seats');

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'success',
            'message',
            'data' => [
                '*' => $this->seatJsonStructure(),
            ],
        ]);
    }

    public function test_admin_can_create_seat()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        Sanctum::actingAs($admin);

        $cinema = Cinema::factory()->create();
        $hall = Hall::factory()->create(['cinema_id' => $cinema->id]);
        $seatCategory = SeatCategory::factory()->create();

        $data = [
            'hall_id' => $hall->id,
            'seat_category_id' => $seatCategory->id,
            'row' => 'A',
            'seat_number' => 1,
            'is_active' => true,
        ];

        $response = $this->postJson('/api/admin/seats', $data);

        $response->assertStatus(201);

        $response->assertJsonStructure([
            'success',
            'message',
            'data' => $this->seatJsonStructure(),
        ]);

        $this->assertDatabaseHas('seats', [
            'hall_id' => $data['hall_id'],
            'seat_category_id' => $data['seat_category_id'],
            'row' => $data['row'],
            'seat_number' => $data['seat_number'],
            'is_active' => $data['is_active'],
        ]);
    }

    public function test_admin_can_show_seat()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        Sanctum::actingAs($admin);

        $seat = $this->createSeat();

        $response = $this->getJson('/api/admin/seats/' . $seat->id);

        $response->assertStatus(200);

        $response->assertJsonStructure();
    }

    public function test_admin_can_update_seat()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        Sanctum::actingAs($admin);

        $seat = $this->createSeat();
        $seatCategory = SeatCategory::factory()->create();

        $data = [
            'seat_category_id' => $seatCategory->id,
            'row' => 'B',
            'seat_number' => 5,
            'is_active' => false,
        ];

        $response = $this->putJson('/api/admin/seats/' . $seat->id, $data);

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'success',
            'message',
            'data' => $this->seatJsonStructure(),
        ]);

        $this->assertDatabaseHas('seats', [
            'id' => $seat->id,
            'hall_id' => $seat->hall_id,
            'seat_category_id' => $data['seat_category_id'],
            'row' => $data['row'],
            'seat_number' => $data['seat_number'],
            'is_active' => $data['is_active'],
        ]);
    }

    public function test_admin_can_delete_seat()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        Sanctum::actingAs($admin);

        $seat = $this->createSeat();

        $response = $this->deleteJson('/api/admin/seats/' . $seat->id);

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'success',
            'message',
        ]);

        $this->assertDatabaseMissing('seats', [
            'id' => $seat->id,
        ]);
    }

    public function test_seat_creation_updates_hall_statistics()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        Sanctum::actingAs($admin);

        $cinema = Cinema::factory()->create();
        $hall = Hall::factory()->create(['cinema_id' => $cinema->id]);
        $seatCategory = SeatCategory::factory()->create();

        $this->createSeat(['hall_id' => $hall->id, 'seat_category_id' => $seatCategory->id]);

        $hall->refresh();

        $this->assertEquals(1, $hall->capacity);
        $this->assertEquals(1, $hall->row_count);
    }

    public function test_seat_update_updates_hall_statistics()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        Sanctum::actingAs($admin);

        $seat = $this->createSeat();
        $seatCategory = SeatCategory::factory()->create();
        $cinema = Cinema::factory()->create();
        $hall = Hall::factory()->create(['cinema_id' => $cinema->id]);

        $data = [
            'seat_category_id' => $seatCategory->id,
            'row' => 'B',
            'seat_number' => 5,
            'hall_id' => $hall->id,
        ];
        
        $this->putJson('/api/admin/seats/' . $seat->id, $data);
        $hall->refresh();

        $this->assertEquals(1, $hall->capacity);
        $this->assertEquals(1, $hall->row_count);
    }

    public function test_seat_update_updates_old_hall_statistics()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        Sanctum::actingAs($admin);

        $cinema = Cinema::factory()->create();
        $oldHall = Hall::factory()->create(['cinema_id' => $cinema->id]);
        $seat = $this->createSeat(['hall_id' => $oldHall->id]);

        $newHall = Hall::factory()->create(['cinema_id' => $cinema->id]);

        $data = [
            'row' => 'B',
            'seat_number' => 5,
            'hall_id' => $newHall->id,
        ];

        $this->putJson('/api/admin/seats/' . $seat->id, $data);
        $oldHall->refresh();

        $this->assertEquals(0, $oldHall->capacity);
        $this->assertEquals(0, $oldHall->row_count);
    }

    public function seatJsonStructure(): array
    {
        return [
            'hall_id',
            'seat_category_id',
            'row',
            'seat_number',
            'is_active',
            'created_at',
            'updated_at',
        ];
    }

    private function createSeat(array $overrides = []): Seat
    {
        $hallId = $overrides['hall_id'] ?? null;
        $seatCategoryId = $overrides['seat_category_id'] ?? null;

        if (! $hallId) {
            $cinema = Cinema::factory()->create();
            $hallId = Hall::factory()->create(['cinema_id' => $cinema->id])->id;
        }

        if (! $seatCategoryId) {
            $seatCategoryId = SeatCategory::factory()->create()->id;
        }

        return Seat::create(array_merge([
            'hall_id' => $hallId,
            'seat_category_id' => $seatCategoryId,
            'row' => 'A',
            'seat_number' => 1,
            'is_active' => true,
        ], $overrides));
    }
}
