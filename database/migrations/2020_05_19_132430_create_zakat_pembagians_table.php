<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateZakatPembagiansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('zakat_pembagians', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('zakat_id');
            $table->unsignedBigInteger('user_id');
            $table->bigInteger('beras');
            $table->bigInteger('uang');
            $table->string('tipe');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('zakat_id')->references('id')->on('zakats')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('zakat_pembagians');
    }
}
