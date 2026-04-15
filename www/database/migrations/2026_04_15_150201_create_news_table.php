<?php

use App\Enums\NewsStateEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique(); // Tratado nos observers caso haja colisões
            $table->text('content');
            $table->string('cover_image')->nullable();
            $table->string('state')->default(NewsStateEnum::DRAFT->value);
            
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->foreignId('author_id')->constrained('users')->cascadeOnDelete();
            
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            
            // Meilisearch Index optimizations
            $table->index(['state', 'published_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};
