<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateSinglesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('singles', function($table)
		{
            $table->increments('id');
            $table->integer('user_id');
            $table->dateTime('date_applied');
            $table->integer('count');
            $table->integer('flock_number');
            $table->string('destination');

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
		Schema::drop('singles');
	}

}
