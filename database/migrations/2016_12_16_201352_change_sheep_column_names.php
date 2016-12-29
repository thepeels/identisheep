<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class ChangeSheepColumnNames extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('sheep', function(Blueprint $table)
        {
            $table->renameColumn('user_id','owner');
            $table->renameColumn('e_flock','flock_number');
            $table->renameColumn('original_e_flock','original_flock_number');
            $table->renameColumn('colour_flock','supplementary_tag_flock_number');
            $table->renameColumn('e_tag','serial_number');
            $table->renameColumn('original_e_tag','original_serial_number');
            $table->renameColumn('e_tag_1','old_serial_number');
            $table->renameColumn('e_tag_2','older_serial_number');
            $table->renameColumn('colour_tag','supplementary_serial_number');
            $table->renameColumn('colour_of_tag','tag_colour');
            $table->renameColumn('off_how','destination');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table('sheep', function($table)
        {
            $table->renameColumn('owner','user_id');
            $table->renameColumn('flock_number','e_flock');
            $table->renameColumn('original_flock_number','original_e_flock');
            $table->renameColumn('supplementary_tag_flock_number','colour_flock');
            $table->renameColumn('serial_number','e_tag');
            $table->renameColumn('original_serial_number','original_e_tag');
            $table->renameColumn('old_serial_number','e_tag_1');
            $table->renameColumn('older_serial_number','e_tag_2');
            $table->renameColumn('supplementary_serial_number','colour_tag');
            $table->renameColumn('tag_colour','colour_of_tag');
            $table->renameColumn('destination','off_how');
        });
	}

}
