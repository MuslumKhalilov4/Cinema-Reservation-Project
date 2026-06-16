<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('movies', function (Blueprint $table) {
            $table->id();
            $table->json('title');
            $table->json('description');
            $table->string('slug')->unique();
            $table->string('poster_url')->nullable();
            $table->string('trailer_url')->nullable();
            $table->integer('duration');
            $table->date('release_date');
            $table->string('country');
            $table->string('language');
            $table->string('director');
            $table->integer('rating_count')->default(0);
            $table->decimal('rating', 2, 1)->default(0);
            $table->integer('age_limit');
            $table->boolean('is_featured')->default(false);
            $table->enum('status', ['upcoming', 'active', 'archived'])->default('upcoming');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movies');
    }
};
