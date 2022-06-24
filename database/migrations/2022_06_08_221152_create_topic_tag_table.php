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
        Schema::create('topic_tag', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('topic_id')->nullable();
            $table->unsignedBigInteger('tag_id')->nullable();
            $table->foreign('topic_id')->references('id')->on('topics')->onDelete('cascade');
            $table->foreign('tag_id')->references('id')->on('tags')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('topic_tag');
    }
};
