<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * spatie/laravel-activitylog v5: tracked model changes live in their own
 * `attribute_changes` column and the batch system (`batch_uuid`) is gone.
 *
 * MySQL cannot roll back DDL, so every step checks whether it has already
 * been applied. A run that died halfway can simply be repeated.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('activity_log', 'attribute_changes')) {
            Schema::table('activity_log', function (Blueprint $table) {
                $table->json('attribute_changes')->nullable()->after('causer_id');
            });
        }

        // Rows that were already converted no longer match, so this is repeatable.
        DB::transaction(function () {
            DB::table('activity_log')
                ->where(function ($query) {
                    $query->whereNotNull('properties->attributes')
                        ->orWhereNotNull('properties->old');
                })
                ->eachById(function ($row) {
                    $properties = json_decode($row->properties, true);
                    $changes = array_intersect_key($properties, array_flip(['attributes', 'old']));
                    $remaining = array_diff_key($properties, array_flip(['attributes', 'old']));

                    DB::table('activity_log')->where('id', $row->id)->update([
                        'attribute_changes' => empty($changes) ? null : json_encode($changes),
                        'properties' => empty($remaining) ? null : json_encode($remaining),
                    ]);
                });
        });

        if (Schema::hasColumn('activity_log', 'batch_uuid')) {
            Schema::table('activity_log', function (Blueprint $table) {
                $table->dropColumn('batch_uuid');
            });
        }
    }

    public function down(): void
    {
        if (! Schema::hasColumn('activity_log', 'batch_uuid')) {
            Schema::table('activity_log', function (Blueprint $table) {
                $table->uuid('batch_uuid')->nullable()->after('properties');
            });
        }

        if (Schema::hasColumn('activity_log', 'attribute_changes')) {
            DB::transaction(function () {
                DB::table('activity_log')
                    ->whereNotNull('attribute_changes')
                    ->eachById(function ($row) {
                        $properties = json_decode($row->properties ?? '[]', true) ?? [];
                        $changes = json_decode($row->attribute_changes, true) ?? [];

                        DB::table('activity_log')->where('id', $row->id)->update([
                            'properties' => json_encode(array_merge($properties, $changes)),
                        ]);
                    });
            });

            Schema::table('activity_log', function (Blueprint $table) {
                $table->dropColumn('attribute_changes');
            });
        }
    }
};
