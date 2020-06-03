<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBantuanImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bantuan_images', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('bantuan_id');
            $table->string('path');
            $table->timestamps();
            $table->foreign('bantuan_id')->references('id')->on('bantuans')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bantuan_images');
    }
}
