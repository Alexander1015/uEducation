<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;
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
                "password" => Hash::make("Soporte2022"),
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
