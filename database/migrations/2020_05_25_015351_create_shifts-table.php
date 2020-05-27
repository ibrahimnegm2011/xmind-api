<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShiftsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shifts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('user_id')->references('id')->on('users');
            $table->foreignId('account_id')->references('id')->on('users');
            $table->date('shift_date');
            $table->string('start', 10);
            $table->string('end', 10);
            $table->enum('status', ['started', 'canceled', 'finished']);
            $table->integer('total_records');
            $table->string('total_times', 10);
            $table->decimal('records_revenue', 8, 2, true);
            $table->decimal('food_revenue', 8, 2, true);
            $table->decimal('total_revenue', 8, 2, true);
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

        Schema::drop('shifts');
    }
}
