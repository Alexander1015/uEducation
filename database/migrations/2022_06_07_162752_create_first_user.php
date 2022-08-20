<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
        DB::table("users")
            ->insert([
                "slug" => Str::slug(Str::random(20)),
                "firstname" => "Soporte",
                "lastname" => "uEducation",
                "email" => "soporte@ueducation.com",
                "user" => strtolower("soporte.ueducation"),
                "password" => '$2y$10$jYwhOPt7oCtrLYOXV.trA.pepU1APJVxWyIW21F1PNVrv2p/PbsJC',
                "avatar" => "",
                "type" => "0",
                "status" => "1",
            ]);
        DB::table("users")
            ->insert([
                "slug" => Str::slug(Str::random(20)),
                "firstname" => "Edgard Alexander",
                "lastname" => "Barrera Flamenco",
                "email" => "alexanderbarrera105@gmail.com",
                "user" => strtolower("BF180436"),
                "password" => '$2y$10$LRG1L8Xe1pu5XaJdMdyPnOv12aZuB.hCOMivQy3oqSdXcQ2Yj7wQG',
                "avatar" => "",
                "type" => "0",
                "status" => "1",
            ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
