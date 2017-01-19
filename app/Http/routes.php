<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'WelcomeController@index');

Route::get('get-started','WelcomeController@getStarted');

Route::get('home', 'HomeController@index');

Route::get('contact', 'HomeController@contact');

Route::get('about', 'WelcomeController@about');

Route::get('homeabout', 'HomeController@about');

Route::get('help/{page}', 'HelpController@index');

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);
Route::get('login','Auth\AuthController@index');

//Route::get('sheep/{print?}', ['as'=>'sheep','uses'=> 'SheepController@getEwes']);

Route::controller('sheep','SheepController');


Route::controller('batch','BatchController');

Route::controller('test','TestController');


Route::controller('list','ListController');

Route::get('back',function(){return redirect()->back();});

Route::controller('subs','SubscriptionsController');


