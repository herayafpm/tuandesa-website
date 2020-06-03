<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateZakatAmilsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('zakat_amils', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('zakat_id');
            $table->unsignedBigInteger('user_id');
            $table->integer('dusun');
            $table->bigInteger('beras');
            $table->bigInteger('uang');
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('zakat_id')->references('id')->on('zakats')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('zakat_amils');
    }
}
