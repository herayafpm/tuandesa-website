<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePelayananImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pelayanan_images', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('pelayanan_id');
            $table->string('path');
            $table->timestamps();
            $table->foreign('pelayanan_id')->references('id')->on('pelayanans')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pelayanan_images');
    }
}
