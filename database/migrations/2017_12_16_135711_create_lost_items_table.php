<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLostItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lost_items', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('category')->unsigned();
            $table->integer('finder')->unsigned();
            $table->integer('owner')->unsigned();
            $table->foreign('category')
                ->references('id')->on('categories')
                ->onDelete('cascade');
            $table->foreign('finder')
                ->references('id')->on('users')
                ->onDelete('cascade');
            $table->foreign('owner')
                ->references('id')->on('users')
                ->onDelete('cascade');
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
        Schema::dropIfExists('lost_items');
    }
}
