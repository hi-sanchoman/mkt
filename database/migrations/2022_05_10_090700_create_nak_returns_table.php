<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNakReturnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nak_returns', function (Blueprint $table) {
            $table->id();
            
            $table->integer('oweshop_id')->unsigned();
            $table->integer('realization_id')->unsigned();
            
            // $table->foreign('oweshop_id')->references('id')->on('oweshops')->onUpdate('cascade')->onDelete('cascade');
            // $table->foreignId('realization_id')->constrained()->onUpdate('cascade')->onDelete('cascade');

            $table->integer('sum')->unsigned();

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
        Schema::dropIfExists('nak_returns');
    }
}
