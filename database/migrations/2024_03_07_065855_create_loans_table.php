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
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->integer('siku');
            $table->integer('kiasi');
            $table->integer('riba')->default(0);
            $table->integer('rejesho')->default(0);
            $table->boolean('mpya')->default(true);
            $table->boolean('hali')->default(false);
            $table->boolean('kasoro')->default(false);
            $table->boolean('njeMuda')->default(false);
            $table->string('maelezo');
            $table->dateTime('mwanzoMkopo')->nullable();
            $table->timestamps();
            $table->foreignId('customer_id');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};
