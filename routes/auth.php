<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

if (isset($router)) {
    $router->group(['prefix' => 'api/auth'], function ($app) {
        $app->post('login/', 'LoginController@login');

        $app->group(['middleware' => 'auth'], function ($appAuth) {
            $appAuth->get('load/user', 'LoginController@loadUser');
        });

    });
}
