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
        Schema::table('reports', function (Blueprint $table) {
            if (! Schema::hasColumn('reports', 'inventory_code')) {
                $table->string('inventory_code', 100)->nullable()->after('item_name');
            }

            $table->string('problem_type', 100)->change();
            $table->string('status', 50)->default('Pending')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            if (Schema::hasColumn('reports', 'inventory_code')) {
                $table->dropColumn('inventory_code');
            }
        });
    }
};
