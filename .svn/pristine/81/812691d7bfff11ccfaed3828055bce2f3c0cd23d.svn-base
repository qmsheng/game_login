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

Route::get('/', function () {
    //return view('welcome');
	Route::any('/', ['uses' => 'IndexController@proload_login']);
});

Route::group(['namespace' => 'Login', 'prefix' => 'login', ], function () {
    Route::any('/', ['uses' => 'IndexController@proload_login']);
});

Route::group(['namespace' => 'Login2', 'prefix' => 'login2', ], function () {
    Route::any('/', ['uses' => 'IndexController@proload_login2']);
});

Route::group(['namespace' => 'Login3', 'prefix' => 'login3', ], function () {
    Route::any('/', ['uses' => 'IndexController@proload_login3']);
});

Route::group(['namespace' => 'Login4', 'prefix' => 'login4', ], function () {
    Route::any('/', ['uses' => 'IndexController@proload_login4']);
});

Route::group(['namespace' => '_public_tur', 'prefix' => 'public_tur', ], function () {
    Route::any('/', ['uses' => 'IndexController@proload_public_tur']);
});

Route::group(['namespace' => '_public_tx', 'prefix' => 'public_tx', ], function () {
    Route::any('/', ['uses' => 'IndexController@proload_public_tx']);
});

Route::group(['namespace' => '_act_preview', 'prefix' => 'act_preview', ], function () {
    Route::any('/', ['uses' => 'IndexController@proload_public_tx']);
});
Route::group(['namespace' => '_department', 'prefix' => 'department', ], function () {
    Route::any('/', ['uses' => 'IndexController@proload_public_tx']);
});

Route::group(['namespace' => '_develop', 'prefix' => 'develop', ], function () {
    Route::any('/', ['uses' => 'IndexController@proload_public_tx']);
});
Route::group(['namespace' => '_release', 'prefix' => 'release', ], function () {
    Route::any('/', ['uses' => 'IndexController@proload_public_tx']);
});
Route::group(['namespace' => '_dev_turkey', 'prefix' => 'dev_turkey', ], function () {
    Route::any('/', ['uses' => 'IndexController@proload_public_tx']);
});
Route::group(['namespace' => '_dev_russia', 'prefix' => 'dev_russia', ], function () {
    Route::any('/', ['uses' => 'IndexController@proload']);
});

Route::group(['namespace' => '_7k7k', 'prefix' => '7k7k', ], function () {
    Route::any('/login.php', ['uses' => 'IndexController@login']);
    Route::any('/check_user.php', ['uses' => 'IndexController@check_user']);
    Route::any('/success_pay.php', ['uses' => 'IndexController@req_charge']);
});

Route::group(['namespace' => 'Oas', 'prefix' => 'oas', ], function () {
    Route::any('/', ['uses' => 'IndexController@oas_login']);
	Route::any('/join', ['uses' => 'IndexController@oas_join']);
	Route::any('/playerquery', ['uses' => 'IndexController@player_query']);
	Route::any('/onlinequery', ['uses' => 'IndexController@online_query']);
	Route::any('/registerquery', ['uses' => 'IndexController@register_query']);
	Route::any('/recharge', ['uses' => 'IndexController@recharge']);
    Route::any('/addlog', ['uses' => 'IndexController@add_log']);
});

Route::group(['namespace' => 'Tencent', ], function () {
    Route::any('/', ['uses' => 'IndexController@proload_login']);
    Route::any('/buy', ['uses' => 'IndexController@buy_good']);
    Route::any('/blueinfo', ['uses' => 'IndexController@blue_info']);
    Route::any('/recharge', ['uses' => 'IndexController@recharge']);
	Route::any('/compass', ['uses' => 'IndexController@proload_compass']);
});

Route::group(['namespace' => 'Tencent', 'prefix' => 'tencent' ], function () {
    Route::any('/', ['uses' => 'IndexController@proload_login']);
    Route::any('/buy', ['uses' => 'IndexController@buy_good']);
    Route::any('/blueinfo', ['uses' => 'IndexController@blue_info']);
    Route::any('/recharge', ['uses' => 'IndexController@recharge']);
        Route::any('/compass', ['uses' => 'IndexController@proload_compass']);
});

Route::group(['namespace' => '_dev_login', 'prefix' => 'dev_login', ], function () {
      Route::any('/login_debug', ['uses' => 'IndexController@login_debug']);
      Route::any('/req_login', ['uses' => 'IndexController@req_login']);
});

Route::group(['namespace' => 'Api', 'prefix' => 'api', ], function () {
      Route::any('/addlog', ['uses' => 'IndexController@add_log']);
//    Route::any('/network_login', ['uses' => 'IndexController@network_login']);
//    Route::any('/login_test', ['uses' => 'IndexController@login']);
//    Route::any('/login', ['uses' => 'IndexController@tencent_login']);
//    Route::any('/rank', ['uses' => 'IndexController@rank']);
});

Route::group(['namespace' => 'Dota', 'prefix' => 'dota', ], function () {
//    Route::any('/', ['uses' => 'IndexController@login']);
//    Route::any('/login', ['uses' => 'IndexController@login']);
//    Route::any('/register', ['uses' => 'IndexController@register']);
});

Route::group(['namespace' => 'Admin', 'prefix' => 'admin', ], function () {
    Route::get('/', ['uses' => 'IndexController@index']);
    Route::get('/home', ['uses' => 'IndexController@home']);
    Route::any('/report', ['uses' => 'MonitorController@report']);
    Route::any('/monitor/index', ['uses' => 'MonitorController@index']);
    Route::any('/monitor/query', ['uses' => 'MonitorController@query']);
});
