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
        Schema::create('tracks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('artist')->nullable()->default(null);
            $table->string('album')->nullable();
            $table->string('audio_file_path');
            $table->string('cover_image_path')->nullable()->default(null);
            $table->enum('visibility', ['private','public'])->default('private');
            $table->unsignedInteger('duration_ms')->nullable();
            $table->unsignedBigInteger('play_count')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tracks');
    }
};
