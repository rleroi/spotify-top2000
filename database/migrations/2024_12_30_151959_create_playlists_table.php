<?php

use App\Models\Playlist;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('playlists', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('image')->nullable();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
        Schema::create('songs', function (Blueprint $table) {
            $table->id();
            $table->string('spotify_id')->index();
            $table->unsignedSmallInteger('playlist_position');
            $table->foreignIdFor(Playlist::class)->constrained()->cascadeOnDelete();
            $table->string('spotify_artist_id');
            $table->string('artist');
            $table->string('name');
            $table->unsignedInteger('length');
            $table->string('image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('songs');
        Schema::dropIfExists('playlists');
    }
};
