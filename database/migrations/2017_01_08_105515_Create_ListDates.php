<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateListDates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('listdates', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('owner');
            $table->dateTime('date_start');
            $table->dateTime('date_end');
            $table->timestamps();
            $table->index('owner');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('listdates');
    }
}
