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
        Schema::create('mapatos', function (Blueprint $table) {
            $table->id();
            $table->integer('kiasi');
            $table->integer('kiasiCopy')->nullable();
            $table->string('njia');
            $table->string('njiaCopy')->nullable();
            $table->string('aina');
            $table->string('ainaCopy')->nullable();
            $table->string('maelezo');
            $table->string('maelezoCopy')->nullable();
            $table->boolean('futa')->default(false);
            $table->boolean('badili')->default(false);
            $table->integer('rejeshoId')->default(0);
            $table->boolean('rejesho')->default(false);
            $table->timestamps();
            $table->foreignId('office_id');
            $table->foreign('office_id')->references('id')->on('offices')->onDelete('cascade');
            $table->foreignId('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mapatos');
    }
};
