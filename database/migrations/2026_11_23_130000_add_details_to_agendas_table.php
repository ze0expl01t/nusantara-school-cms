<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('agendas')) {
            return;
        }

        Schema::table('agendas', function (Blueprint $table) {
            if (! Schema::hasColumn('agendas', 'time_slot')) {
                $table->string('time_slot', 100)->nullable()->after('agenda_date');
            }
            if (! Schema::hasColumn('agendas', 'venue')) {
                $table->string('venue', 255)->nullable()->after('location');
            }
            if (! Schema::hasColumn('agendas', 'organizer')) {
                $table->string('organizer', 255)->nullable()->after('venue');
            }
            if (! Schema::hasColumn('agendas', 'participants')) {
                $table->string('participants', 255)->nullable()->after('organizer');
            }
            if (! Schema::hasColumn('agendas', 'external_url')) {
                $table->string('external_url', 2048)->nullable()->after('participants');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('agendas')) {
            return;
        }

        Schema::table('agendas', function (Blueprint $table) {
            foreach (['external_url', 'participants', 'organizer', 'venue', 'time_slot'] as $col) {
                if (Schema::hasColumn('agendas', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
