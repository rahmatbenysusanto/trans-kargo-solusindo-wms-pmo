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
        Schema::table('product', function (Blueprint $table) {
            $table->text('part_description')->nullable()->after('part_name');
        });

        Schema::table('inbound_detail', function (Blueprint $table) {
            $table->text('part_description')->nullable()->after('part_number');
        });

        Schema::table('inventory', function (Blueprint $table) {
            $table->text('part_description')->nullable()->after('part_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product', function (Blueprint $table) {
            $table->dropColumn('part_description');
        });

        Schema::table('inbound_detail', function (Blueprint $table) {
            $table->dropColumn('part_description');
        });

        Schema::table('inventory', function (Blueprint $table) {
            $table->dropColumn('part_description');
        });
    }
};
