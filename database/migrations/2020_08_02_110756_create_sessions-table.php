<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sessions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('user_id')->references('id')->on('users');
            $table->foreignId('shift_id')->references('id')->on('shifts');
            $table->datetime('session_start');
            $table->datetime('session_end')->nullable();
            $table->enum('status', ['opened', 'closed'])->default('opened');
            $table->string('client_name', 100)->default('');
            $table->integer('persons_no')->default(1);
            $table->decimal('device_price', 8, 2, true)->default(0);
            $table->decimal('food_price', 8, 2, true)->default(0);
            $table->decimal('total_price', 8, 2, true)->default(0);
            $table->decimal('paid', 8, 2, true)->default(0);
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
        Schema::drop('sessions');
    }
}