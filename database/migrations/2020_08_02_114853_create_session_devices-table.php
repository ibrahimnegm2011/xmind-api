<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSessionDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('session_devices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('user_id')->references('id')->on('users');
            $table->foreignId('shift_id')->references('id')->on('shifts');
            $table->foreignId('session_id')->references('id')->on('sessions');
            $table->foreignId('device_id')->references('id')->on('devices');
            $table->dateTime('start');
            $table->datetime('end')->nullable();
            $table->string('time_spent', 10)->default("00:00");
            $table->boolean('is_multi')->default(false);
            $table->decimal('hour_rate', 8, 2, true)->default(0);
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
        Schema::drop('session_devices');
    }
}
