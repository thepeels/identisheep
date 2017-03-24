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
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, Mandrill, and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'to' => \Carbon\Carbon::now()->toDateString(),
    'from'=>\Carbon\Carbon::now()->subYears(10)->toDateString(),



];