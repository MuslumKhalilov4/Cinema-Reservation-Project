<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Database\Seeders\RolePermissionSeeder;
use App\Models\{User, Movie, Genre, Actor};
use Laravel\Sanctum\Sanctum;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MovieTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    public function test_admin_can_get_movies()
    {
        $admin = User::factory()->create();
        Movie::factory()->count(3)->create();
        $admin->assignRole('admin');

        Sanctum::actingAs($admin);

        $response = $this->getJson('/api/admin/movies');

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'success',
            'message',
            'data' => [
                '*' => $this->movieListJsonStructure(),
            ],
            'links',
            'meta',
        ]);
    }

    public function test_admin_can_create_movie()
    {
        $storage = Storage::fake('public');
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $genre = Genre::factory()->create();
        $actor = Actor::factory()->create();

        Sanctum::actingAs($admin);

        $data = [
            'title' => [
                'az' => 'Test Movie',
                'en' => 'Test Movie',
                'ru' => 'Test Movie',
            ],
            'description' => [
                'az' => 'Test Description',
                'en' => 'Test Description',
                'ru' => 'Test Description',
            ],
            'poster' => UploadedFile::fake()->image('poster.jpg'),
            'duration' => 120,
            'release_date' => '2000-01-01',
            'country' => 'Test Country',
            'language' => 'en',
            'director' => 'Test Director',
            'age_limit' => 12,
            'genre_ids' => [$genre->id],
            'actors' => [
                [
                    'actor_id' => $actor->id,
                    'character_name' => 'Test Character',
                ],
            ],
        ];

        $response = $this->postJson('/api/admin/movies', $data);

        $response->assertStatus(201);

        $response->assertJsonStructure([
            'success',
            'message',
            'data' => $this->movieDetailJsonStructure(),
        ]);

        $storage->assertExists('movies/' . $data['poster']->hashName());

        $this->assertDatabaseHas('movies', [
            'title->en' => $data['title']['en'],
            'description->en' => $data['description']['en'],
            'slug' => Str::slug($data['title']['en']),
            'duration' => $data['duration'],
            'release_date' => $data['release_date'],
            'country' => $data['country'],
            'language' => $data['language'],
            'director' => $data['director'],
            'age_limit' => $data['age_limit'],
            'poster_url' => 'movies/' . $data['poster']->hashName(),
        ]);

        $movie = Movie::first();

        $this->assertDatabaseHas('movie_genre', [
            'movie_id' => $movie->id,
            'genre_id' => $genre->id,
        ]);

        $this->assertDatabaseHas('cast', [
            'movie_id' => $movie->id,
            'actor_id' => $actor->id,
        ]);
    }

    public function test_admin_can_show_movie()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        Sanctum::actingAs($admin);

        $movie = Movie::factory()->create();

        $response = $this->getJson('/api/admin/movies/' . $movie->id);

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'success',
            'message',
            'data' => $this->movieDetailJsonStructure(),
        ]);
    }

    public function test_admin_can_update_movie()
    {
        $storage = Storage::fake('public');
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        Sanctum::actingAs($admin);

        $movie = Movie::factory()->create();
        $genre = Genre::factory()->create();

        $data = [
            'title' => [
                'az' => 'Updated Movie',
                'en' => 'Updated Movie',
                'ru' => 'Updated Movie',
            ],
            'description' => [
                'az' => 'Updated Description',
                'en' => 'Updated Description',
                'ru' => 'Updated Description',
            ],
            'poster' => UploadedFile::fake()->image('poster.jpg'),
            'duration' => 150,
            'release_date' => '2001-01-01',
            'country' => 'Updated Country',
            'language' => 'ru',
            'director' => 'Updated Director',
            'age_limit' => 16,
            'genre_ids' => [$genre->id],
        ];

        $response = $this->putJson('/api/admin/movies/' . $movie->id, $data);

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'success',
            'message',
            'data' => $this->movieDetailJsonStructure(),
        ]);

        $storage->assertExists('movies/' . $data['poster']->hashName());

        $this->assertDatabaseHas('movies', [
            'id' => $movie->id,
            'title->en' => $data['title']['en'],
            'description->en' => $data['description']['en'],
            'slug' => Str::slug($data['title']['en']),
            'duration' => $data['duration'],
            'release_date' => $data['release_date'],
            'country' => $data['country'],
            'language' => $data['language'],
            'director' => $data['director'],
            'age_limit' => $data['age_limit'],
            'poster_url' => 'movies/' . $data['poster']->hashName(),
        ]);
    }

    public function test_admin_can_delete_movie()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        Sanctum::actingAs($admin);

        $movie = Movie::factory()->create();

        $response = $this->deleteJson('/api/admin/movies/' . $movie->id);

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'success',
            'message',
        ]);

        $this->assertDatabaseMissing('movies', [
            'id' => $movie->id,
        ]);
    }

    public function movieListJsonStructure(): array
    {
        return [
            'id',
            'title',
            'description',
            'slug',
            'poster_url',
            'trailer_url',
            'duration',
            'release_date',
            'country',
            'language',
            'director',
            'rating_count',
            'rating',
            'age_limit',
            'is_featured',
            'status',
            'created_at',
            'updated_at',
        ];
    }

    public function movieDetailJsonStructure(): array
    {
        return [
            ...$this->movieListJsonStructure(),
            'genres',
            'actors',
        ];
    }
}
