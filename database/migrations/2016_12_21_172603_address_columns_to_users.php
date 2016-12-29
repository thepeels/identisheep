<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class AddressColumnsToUsers extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table('users',function($table){
            $table->string('business')->after('flock');
            $table->string('address')->after('business');
            $table->string('holding')->after('address');

        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table('users',function($table){
            $table->dropColumn('business');
            $table->dropColumn('address');
            $table->dropColumn('holding');
        });
	}

}
