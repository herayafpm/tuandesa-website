<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBantuanPemeringkatanDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bantuan_pemeringkatan_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('bantuan_pemeringkatan_id');
            $table->unsignedBigInteger('bantuan_id');
            $table->bigInteger('peringkat');
            $table->float('total');
            $table->foreign('bantuan_id')->references('id')->on('bantuans')->onDelete('cascade');
            $table->foreign('bantuan_pemeringkatan_id')->references('id')->on('bantuan_pemeringkatans')->onDelete('cascade');
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
        Schema::dropIfExists('bantuan_pemeringkatan_details');
    }
}
