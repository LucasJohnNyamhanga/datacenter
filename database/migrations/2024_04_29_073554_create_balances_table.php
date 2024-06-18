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
        Schema::create('balances', function (Blueprint $table) {
            $table->id();
            $table->integer('kiasiSystem');
            $table->integer('kiasiUser');
            $table->string('aina');
            $table->string('maelezo');
            $table->dateTime('tareheHesabu');
            $table->dateTime('tareheHesabuIliyopita');
            $table->integer('hesabuIliyopita');
            $table->boolean('imeharibika')->default(false);
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
        Schema::dropIfExists('balances');
    }
};
