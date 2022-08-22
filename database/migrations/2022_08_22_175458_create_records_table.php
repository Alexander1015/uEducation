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
        Schema::create('records', function (Blueprint $table) {
            $table->id();
            $table->string('firstname')->default("");
            $table->string('lastname')->default("");
            $table->string('user')->default("");
            $table->boolean('status_user')->default(1);
            $table->string('subject')->default("");
            $table->string('code')->default("");
            $table->boolean('status_subject')->default(1);
            $table->string('period')->default("");
            $table->integer('year')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('records');
    }
};
