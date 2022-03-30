<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTarasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('taras', function (Blueprint $table) {
            $table->id();
            $table->string('name',200);
            $table->integer('amount');
            $table->integer('inside');
            $table->integer('total');
            $table->integer('price');
            $table->integer('sum');
            $table->integer('need');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('taras');
    }
}
