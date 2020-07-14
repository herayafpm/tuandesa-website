<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterColumnInBantuanPemeringkatans extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bantuan_pemeringkatans', function (Blueprint $table) {
            // $table->dropColumn('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bantuan_pemeringkatans', function (Blueprint $table) {
            // $table->dropColumn('user');
        });
    }
}
