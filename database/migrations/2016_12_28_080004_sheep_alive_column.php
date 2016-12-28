<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SheepAliveColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sheep',function($table){
            $table->boolean('alive')->default(TRUE)->after('move_on');
            $table->index('alive');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sheep',function($table){
            $table->dropColumn('alive');
        });
    }
}
