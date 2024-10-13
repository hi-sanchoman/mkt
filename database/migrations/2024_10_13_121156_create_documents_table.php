<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id(); // Primary key (id)
            $table->string('name'); // Document name
            $table->text('desc')->nullable(); // Description (nullable)
            $table->unsignedBigInteger('user_id'); // Foreign key for user
            $table->string('src'); // Path for the uploaded file
            $table->timestamps(); // created_at and updated_at
            $table->softDeletes(); // deleted_at (for soft deletes)

            // Foreign key constraint 
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('documents');
    }
}
