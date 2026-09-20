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
        Schema::create('medicines', function (Blueprint $table) {
            $table->id();
            $table->string('name_ar');
            $table->string('name_en');
            $table->string('description');
            $table->string('factory');
            $table->longText('composition');
            $table->string('concentration');
            $table->string('pharmaceutical_form');
            $table->string('package');
            $table->decimal('price',12,2 )->default(0.00);
            $table->unsignedBigInteger('order_qty')->default(0);
            $table->unsignedBigInteger('available_qty')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicines');
    }
};
