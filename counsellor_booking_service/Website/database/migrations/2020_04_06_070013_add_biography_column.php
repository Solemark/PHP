<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBiographyColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists("biography");

        Schema::table("users", function (Blueprint $table) {
            $table->string("bio")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("users", function (Blueprint $table) {
            $table->dropColumn("bio");
        });

        Schema::create('biography', function (Blueprint $table) {
            $table->increments("id");
            $table->integer("psychologist_id");
            $table->longText("details")->nullable();
            $table->text("keywords")->nullable();

        });
    }
}
