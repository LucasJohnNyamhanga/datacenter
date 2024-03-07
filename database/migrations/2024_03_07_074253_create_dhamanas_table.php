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
        Schema::create('dhamanas', function (Blueprint $table) {
            $table->id();
            $table->string('jina');
            $table->string('thamani');
            $table->string('maelezo');
            $table->string('picha');
            $table->timestamps();
            $table->foreignId('loans_id');
            $table->foreign('loans_id')->references('id')->on('loans')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dhamanas');
    }
};
