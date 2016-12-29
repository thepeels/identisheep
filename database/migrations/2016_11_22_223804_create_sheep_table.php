<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateSheepTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('sheep', function($table)
        {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('local_id');
            $table->dateTime('move_on');
            $table->dateTime('move_off');
            $table->string('off_how',32);
            $table->string('e_flock',32);
            $table->string('original_e_flock',32);
            $table->string('colour_flock',32);
            $table->smallInteger('e_tag');
            $table->smallInteger('e_tag_1');
            $table->smallInteger('e_tag_2');
            $table->smallInteger('original_e_tag');
            $table->smallInteger('colour_tag');
            $table->smallInteger('colour_tag_1');
            $table->smallInteger('colour_tag_2');
            $table->string('sex')->default('female');
            $table->string('colour_of_tag',20);
            $table->timestamps();
            $table->softDeletes();
            $table->index('user_id');
            $table->index('local_id');
            $table->index('deleted_at');
        });
        DB::unprepared("ALTER TABLE  `sheep` CHANGE  `e_tag`  `e_tag` SMALLINT( 5 ) UNSIGNED ZEROFILL DEFAULT '0';");
        DB::unprepared("ALTER TABLE  `sheep` CHANGE  `e_tag_1`  `e_tag_1` SMALLINT( 5 ) UNSIGNED ZEROFILL DEFAULT '0';");
        DB::unprepared("ALTER TABLE  `sheep` CHANGE  `e_tag_2`  `e_tag_2` SMALLINT( 5 ) UNSIGNED ZEROFILL DEFAULT '0';");
        DB::unprepared("ALTER TABLE  `sheep` CHANGE  `original_e_tag`  `original_e_tag` SMALLINT( 5 ) UNSIGNED ZEROFILL DEFAULT '0';");
        DB::unprepared("ALTER TABLE  `sheep` CHANGE  `colour_tag`  `colour_tag` SMALLINT( 5 ) UNSIGNED ZEROFILL DEFAULT '0';");
        DB::unprepared("ALTER TABLE  `sheep` CHANGE  `colour_tag_1`  `colour_tag_1` SMALLINT( 5 ) UNSIGNED ZEROFILL DEFAULT '0';");
        DB::unprepared("ALTER TABLE  `sheep` CHANGE  `colour_tag_2`  `colour_tag_2` SMALLINT( 5 ) UNSIGNED ZEROFILL DEFAULT '0';");
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::drop('sheep');
	}

}
