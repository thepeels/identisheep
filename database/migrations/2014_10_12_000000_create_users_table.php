<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
//use Doctrine\DBAL\Schema\Schema;


class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name');
			$table->string('email')->unique();
            $table->string('oauth_email',255);
            $table->boolean('both_emails')->default(FALSE);
            $table->boolean('superuser')->default(FALSE);
			$table->string('password', 60);
			$table->rememberToken();
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
		Schema::drop('users');
	}

}
