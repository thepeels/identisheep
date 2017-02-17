<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CountryCodeToSheep extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sheep', function(Blueprint $table){
            $table->string('country_code')->after('destination');
        });
        DB::table('sheep')->where('id','>',0)
            ->update(['country_code' => 'UK0']);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sheep', function(Blueprint $table){
            $table->dropColumn('country_code');
        });
    }
}
