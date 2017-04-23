<?php
/**
 * Created by PhpStorm.
 * User: John
 * Date: 24/03/2017
 * Time: 22:15
 */
return [

    /*
    |--------------------------------------------------------------------------
    | Default dates for lists
    |--------------------------------------------------------------------------
    |
    | dates where session not available because user has only just logged in.
    |
    */

    'to' => \Carbon\Carbon::now()->toDateString(),
    'from'=>\Carbon\Carbon::now()->subYears(10)->toDateString(),



];