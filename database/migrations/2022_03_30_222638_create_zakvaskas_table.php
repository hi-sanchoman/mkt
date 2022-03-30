<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateZakvaskasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('zakvaskas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('assortment')->index()->nullable();
            $table->integer('zakvaska_id')->index()->nullable();
            $table->decimal('kg', $precision = 10, $scale = 2)->nullable();
            $table->tinyInteger('status')->default(0);
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
        Schema::dropIfExists('zakvaskas');
    }
}
