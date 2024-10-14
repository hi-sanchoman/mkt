<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterNaks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('naks', function (Blueprint $table) {
            $table->tinyInteger('need_check')->default(0)->comment('Нужен чек');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('naks', function (Blueprint $table) {
            $table->dropColumn('need_check');
        });
    }
}
