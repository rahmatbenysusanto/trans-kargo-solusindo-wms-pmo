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
        Schema::create('inbound_detail', function (Blueprint $table) {
            $table->id();
            $table->integer('inbound_id');
            $table->integer('product_id');
            $table->integer('qty');
            $table->integer('qty_pa')->default(0);
            $table->string('part_name');
            $table->string('part_number');
            $table->string('serial_number');
            $table->string('condition')->nullable();
            $table->string('manufacture_date')->nullable();
            $table->string('warranty_end_date')->nullable();
            $table->string('eos_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inbound_detail');
    }
};
