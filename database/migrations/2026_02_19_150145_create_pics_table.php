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
        Schema::create('pics', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::table('inbound', function (Blueprint $table) {
            $table->unsignedBigInteger('pic_id')->nullable()->after('client_id');
        });

        Schema::table('inventory', function (Blueprint $table) {
            $table->unsignedBigInteger('pic_id')->nullable()->after('inbound_detail_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inventory', function (Blueprint $table) {
            $table->dropColumn('pic_id');
        });

        Schema::table('inbound', function (Blueprint $table) {
            $table->dropColumn('pic_id');
        });

        Schema::dropIfExists('pics');
    }
};
