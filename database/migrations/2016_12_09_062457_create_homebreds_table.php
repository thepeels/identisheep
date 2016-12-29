<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateHomebredsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('homebreds', function($table)
		{
			$table->increments('id');
            $table->integer('user_id');
            $table->integer('e_flock');
            $table->integer('count');
            $table->dateTime('date_applied');
			$table->timestamps();
            $table->index('user_id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('homebreds');
	}

}
