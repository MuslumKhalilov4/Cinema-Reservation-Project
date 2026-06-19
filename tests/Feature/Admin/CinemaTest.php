<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Database\Seeders\RolePermissionSeeder;
use App\Models\{User, Cinema};
use Laravel\Sanctum\Sanctum;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CinemaTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    public function test_admin_can_get_cinemas()
    {
        $admin = User::factory()->create();
        Cinema::factory()->count(3)->create();
        $admin->assignRole('admin');

        Sanctum::actingAs($admin);

        $response = $this->getJson('/api/admin/cinemas');

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'success',
            'message',
            'data' => [
                '*' => $this->cinemaJsonStructure(),
            ],
        ]);
    }

    public function test_admin_can_create_cinema()
    {
        $storage = Storage::fake('public');
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        Sanctum::actingAs($admin);

        $data = [
            'name' => 'Test Cinema',
            'about' => [
                'az' => 'Test About',
                'en' => 'Test About',
                'ru' => 'Test About',
            ],
            'address' => 'Test Address',
            'phone' => '+994500001234',
            'email' => 'test@example.com',
            'image' => UploadedFile::fake()->image('test.jpg'),
            'city' => 'Test City',
        ];

        $response = $this->postJson('/api/admin/cinemas', $data);

        $response->assertStatus(201);

        $response->assertJsonStructure([
            'success',
            'message',
            'data' => $this->cinemaJsonStructure(),
        ]);

        $storage->assertExists('cinemas/' . $data['image']->hashName());
    }

    public function test_admin_can_show_cinema()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        Sanctum::actingAs($admin);

        $cinema = Cinema::factory()->create();

        $response = $this->getJson('/api/admin/cinemas/' . $cinema->id);

        $response->assertStatus(200);

        $response->assertJsonStructure();
    }

    public function test_admin_can_update_cinema()
    {
        $storage = Storage::fake('public');
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        Sanctum::actingAs($admin);

        $cinema = Cinema::factory()->create();

        $data = [
            'name' => 'Updated Cinema',
            'about' => [
                'az' => 'Updated About',
                'en' => 'Updated About',
                'ru' => 'Updated About',
            ],
            'address' => 'Updated Address',
            'phone' => '+994500009999',
            'email' => 'updated@example.com',
            'image' => UploadedFile::fake()->image('test.jpg'),
        ];

        $response = $this->putJson('/api/admin/cinemas/' . $cinema->id, $data);

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'success',
            'message',
            'data' => $this->cinemaJsonStructure(),
        ]);

        $storage->assertExists('cinemas/' . $data['image']->hashName());

        $this->assertDatabaseHas('cinemas', [
            'id' => $cinema->id,
            'name' => $data['name'],
            'about->en' => $data['about']['en'],
            'about->az' => $data['about']['az'],
            'about->ru' => $data['about']['ru'],
            'address' => $data['address'],
            'phone' => $data['phone'],
            'email' => $data['email'],
            'slug' => Str::slug($data['name']),
            'image_url' => 'cinemas/' . $data['image']->hashName(),
        ]);
    }

    public function test_admin_can_delete_cinema()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        Sanctum::actingAs($admin);

        $cinema = Cinema::factory()->create();

        $response = $this->deleteJson('/api/admin/cinemas/' . $cinema->id);

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'success',
            'message',
        ]);

        $this->assertDatabaseMissing('cinemas', [
            'id' => $cinema->id,
        ]);
    }

    public function cinemaJsonStructure(): array
    {
        return [
            'name',
            'slug',
            'about',
            'address',
            'phone',
            'email',
            'image_url',
            'city',
            'created_at',
            'updated_at',
        ];
    }
}
