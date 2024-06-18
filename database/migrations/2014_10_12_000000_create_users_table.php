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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('mobile');
            $table->string('jinaKamili');
            $table->string('jinaMdhamini');
            $table->string('simuMdhamini');
            $table->string('picha')->nullable();
            $table->boolean('isManager')->default(false);
            $table->boolean('isAdmin')->default(false);
            $table->string('username')->unique();
            $table->boolean('isActive')->default(false);
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
            $table->foreignId('department_id');
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
            $table->foreignId('office_id');
            $table->foreign('office_id')->references('id')->on('offices')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
