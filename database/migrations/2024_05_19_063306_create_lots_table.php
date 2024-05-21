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
        Schema::create('lots', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('seller');
            $table->unsignedBigInteger('buyer')->nullable();
            $table->string('title');
            $table->string('description');
            $table->string('status')->default('active');
            $table->integer('start_cost');
            $table->integer('last_bet')->nullable();
            $table->integer('bet_step');
            $table->unsignedBigInteger('category_id');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lots');
    }
};
