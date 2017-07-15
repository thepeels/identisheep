<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class StartNumberToHomebreds extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('homebreds', function(Blueprint $table){
            $table->integer('start')->after('count');
            $table->integer('finish')->after('start');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('homebreds',function(Blueprint $table){
            $table->dropColumn('start');
            $table->dropColumn('finish');
        });
    }
}
