<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfileDesaImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profile_desa_images', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('profile_desa_id');
            $table->text('path');
            $table->foreign('profile_desa_id')->references('id')->on('profile_desas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('profile_desa_images');
    }
}
