<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('habits', function (Blueprint $table) {
            $table->index(['user_id', 'is_active']);
        });

        Schema::table('challenges', function (Blueprint $table) {
            $table->index(['user_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::table('habits', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'is_active']);
        });

        Schema::table('challenges', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'is_active']);
        });
    }
};
