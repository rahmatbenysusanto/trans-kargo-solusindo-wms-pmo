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
        Schema::create('inventory', function (Blueprint $table) {
            $table->id();
            $table->integer('product_id');
            $table->integer('client_id');
            $table->integer('inbound_detail_id');
            $table->integer('bin_id');
            $table->integer('qty')->default(0);
            $table->string('status');
            $table->string('part_name');
            $table->string('part_number');
            $table->string('serial_number');
            $table->string('condition')->nullable();
            $table->string('manufacture_date')->nullable();
            $table->string('warranty_end_date')->nullable();
            $table->string('eos_date')->nullable();
            $table->string('pic');
            $table->text('remark')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory');
    }
};
