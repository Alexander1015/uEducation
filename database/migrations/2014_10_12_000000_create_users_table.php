<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique()->default("");
            $table->string('firstname')->default("");
            $table->string('lastname')->default("");
            $table->string('user')->unique()->default("");
            $table->string('email')->unique()->default("");
            $table->string('password')->default("");
            $table->string('avatar')->default("");
            $table->boolean('type')->default(1); // 0 -> Administrador, 1 -> Docente, 2 -> Estudiante
            $table->boolean('status')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
