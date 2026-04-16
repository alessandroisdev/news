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
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('image_path');
            $table->string('target_url')->nullable(); // Link externo se existir
            $table->foreignId('news_id')->nullable()->constrained()->nullOnDelete(); // Ou link interno pra pauta
            
            // Scheduling System
            $table->boolean('is_active')->default(true);
            $table->json('active_days')->nullable(); // ex: [1,2,3,4,5] (Seg a Sex)
            $table->json('active_hours')->nullable(); // ex: ["08:00-12:00", "14:00-18:00"]
            
            // Analytics
            $table->unsignedBigInteger('views')->default(0);
            $table->unsignedBigInteger('clicks')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};
