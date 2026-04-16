<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contact_messages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('subject')->nullable();
            $table->text('message');
            $table->string('ip_address')->nullable();
            $table->boolean('is_read')->default(false);
            $table->timestamps();
            
            // Soft deletes to keep history if user wants to empty inbox but keep for audits
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_messages');
    }
};
