<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSessionFoodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('session_foods', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('user_id')->references('id')->on('users');
            $table->foreignId('shift_id')->references('id')->on('shifts');
            $table->foreignId('session_id')->references('id')->on('sessions');
            $table->foreignId('item_id')->references('id')->on('food_stuff');
            $table->integer('quantity')->default(1);
            $table->decimal('item_price', 8, 2, true)->default(0);
            $table->decimal('cost', 8, 2, true)->default(0);
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
        Schema::drop('session_foods');
    }

}
