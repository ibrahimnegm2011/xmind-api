<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFoodStuffTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('food_stuff', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('account_id')->references('id')->on('users');
            $table->string('name', 100);
            $table->decimal('price', 8, 2, true)->default(0);
            $table->text('note');
            $table->boolean('active')->default(true);
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

        Schema::drop('food_stuff');
    }
}
