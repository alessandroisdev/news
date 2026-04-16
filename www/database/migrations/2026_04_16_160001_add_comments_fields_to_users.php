<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('comment_strikes')->default(0)->after('role');
            $table->boolean('banned_from_comments')->default(false)->after('comment_strikes');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['comment_strikes', 'banned_from_comments']);
        });
    }
};
