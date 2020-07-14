<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBantuanPemeringkatansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bantuan_pemeringkatans', function (Blueprint $table) {
            $table->id();
            $table->text('judul');
            $table->unsignedBigInteger('jenis_bantuan_id');
            $table->timestamp('start',0);
            $table->timestamp('end',0);
            $table->timestamps();
            $table->foreign('jenis_bantuan_id')->references('id')->on('jenis_bantuans')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bantuan_pemeringkatans');
    }
}
