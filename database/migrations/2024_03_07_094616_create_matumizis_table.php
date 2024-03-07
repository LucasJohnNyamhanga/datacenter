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
        Schema::create('matumizis', function (Blueprint $table) {
            $table->id();
            $table->string('kiasi');
            $table->string('njia');
            $table->string('aina');
            $table->string('maelezo');
            $table->timestamps();
            $table->foreignId('offices_id');
            $table->foreign('offices_id')->references('id')->on('offices')->onDelete('cascade');
            $table->foreignId('users_id');
            $table->foreign('users_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('matumizis');
    }
};
