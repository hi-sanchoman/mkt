<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuppliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supplies', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('supplier')->index();
            $table->integer('phys_weight');
            $table->decimal('fat',$precision = 10, $scale = 2);
            $table->integer('acid');
            $table->integer('density');
            $table->decimal('basic_weight',$precision = 10, $scale = 2);
            $table->decimal('fat_kilo',$precision = 10, $scale = 2);
            $table->integer('price');
            $table->integer('sum');
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
        Schema::dropIfExists('supplies');
    }
}
