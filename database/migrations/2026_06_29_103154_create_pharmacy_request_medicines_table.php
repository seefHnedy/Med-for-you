<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pharmacy_request_medicines', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pharmacy_request_id');
            $table->foreign('pharmacy_request_id')->references('id')->on('pharmacy_requests')->cascadeOnDelete();
            $table->unsignedBigInteger('medicine_id');
            $table->foreign('medicine_id')->references('id')->on('medicines')->cascadeOnDelete();
            $table->decimal('price', 12, 2);
            $table->integer('qty');
            $table->decimal('total_price', 12, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pharmacy_request_medicines');
    }
};
