<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;

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
                "firstname" => "Soporte",
                "lastname" => "uEducation",
                "email" => "soporte@ueducation.com",
                "user" => "soporte.ueducation",
                "password" => '$2y$10$jYwhOPt7oCtrLYOXV.trA.pepU1APJVxWyIW21F1PNVrv2p/PbsJC',
                "avatar" => "",
            ]);
        DB::table("users")
            ->insert([
                "firstname" => "Edgard Alexander",
                "lastname" => "Barrera Flamenco",
                "email" => "alexanderbarrera105@gmail.com",
                "user" => "bf180436",
                "password" => '$2y$10$LRG1L8Xe1pu5XaJdMdyPnOv12aZuB.hCOMivQy3oqSdXcQ2Yj7wQG',
                "avatar" => "",
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
