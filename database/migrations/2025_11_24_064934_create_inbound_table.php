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
        Schema::create('inbound', function (Blueprint $table) {
            $table->id();
            $table->string('number');
            $table->integer('client_id');
            $table->string('site_location')->nullable();
            $table->string('inbound_type');
            $table->string('owner_status');
            $table->integer('quantity');
            $table->string('status');
            $table->text('remarks')->nullable();
            $table->timestamp('received_at')->nullable();
            $table->integer('created_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inbound');
    }
};
