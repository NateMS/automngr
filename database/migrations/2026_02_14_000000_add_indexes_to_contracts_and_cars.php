<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contracts', function (Blueprint $table) {
            $table->index('date');
            $table->index('type');
        });

        Schema::table('cars', function (Blueprint $table) {
            $table->index('stammnummer');
        });
    }

    public function down(): void
    {
        Schema::table('contracts', function (Blueprint $table) {
            $table->dropIndex(['date']);
            $table->dropIndex(['type']);
        });

        Schema::table('cars', function (Blueprint $table) {
            $table->dropIndex(['stammnummer']);
        });
    }
};
