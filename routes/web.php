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
    $router->get('/', function () use ($router) {
        return $router->app->version();
    });

    $router->group(['prefix' => 'api/', 'middleware' => 'auth'], function ($app) {

        $app->group(['prefix' => 'shifts/'], function ($router) {
            $router->post('index', 'ShiftsController@index');
            $router->get('current', 'ShiftsController@current');
            $router->post('create', 'ShiftsController@create');
        });
    });
}
