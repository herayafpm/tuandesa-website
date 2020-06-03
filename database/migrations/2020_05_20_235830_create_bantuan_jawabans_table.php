<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBantuanJawabansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bantuan_jawabans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('bantuan_id');
            $table->unsignedBigInteger('soal_jawaban_id');
            $table->unsignedBigInteger('jawaban_id');
            $table->timestamps();
            $table->foreign('bantuan_id')->references('id')->on('bantuans')->onDelete('cascade');
            $table->foreign('soal_jawaban_id')->references('id')->on('soal_jawabans')->onDelete('cascade');
            $table->foreign('jawaban_id')->references('id')->on('jawabans')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bantuan_jawabans');
    }
}
