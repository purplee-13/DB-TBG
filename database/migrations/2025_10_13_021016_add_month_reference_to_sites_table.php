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
        if (!Schema::hasColumn('sites', 'month_reference')) {
            Schema::table('sites', function (Blueprint $table) {
                $table->string('month_reference')->nullable()->after('tikor');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('sites', 'month_reference')) {
            Schema::table('sites', function (Blueprint $table) {
                $table->dropColumn('month_reference');
            });
        }
    }
};
