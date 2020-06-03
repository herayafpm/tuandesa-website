<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAduanImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aduan_images', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('aduan_id');
            $table->string('path');
            $table->timestamps();
            $table->foreign('aduan_id')->references('id')->on('aduans')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('aduan_images');
    }
}
