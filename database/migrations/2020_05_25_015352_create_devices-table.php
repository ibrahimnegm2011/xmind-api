<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('devices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('account_id')->references('id')->on('users');
            $table->string('name', 100);
            $table->string('type', 100);
            $table->text('note');
            $table->boolean('active')->default(true);
            $table->decimal('normal_hour_rate', 8, 2, true)->default(0);
            $table->boolean('has_multi')->default(true);
            $table->decimal('multi_hour_rate', 8, 2, true)->default(0);
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

        Schema::drop('devices');
    }
}
