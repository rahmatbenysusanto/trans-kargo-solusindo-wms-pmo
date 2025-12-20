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
        Schema::create('outbound', function (Blueprint $table) {
            $table->id();
            $table->string('number');
            $table->integer('client_id');
            $table->string('site_location')->nullable();
            $table->enum('type', ['outbound', 'return']);
            $table->integer('qty')->default(0);
            $table->timestamp('delivery_date')->nullable();
            $table->string('received_by')->nullable();
            $table->string('courier')->nullable();
            $table->string('tracking_number')->nullable();
            $table->text('remarks')->nullable();
            $table->integer('created_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('outbound');
    }
};
