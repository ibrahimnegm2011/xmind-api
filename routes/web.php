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
            $router->post('end', 'ShiftsController@end');
        });

        $app->group(['prefix' => 'sessions/'], function ($router) {
            $router->post('create', 'SessionsController@create');
            $router->post('addDevice', 'SessionsController@addDevice');
            $router->post('addFood', 'SessionsController@addFood');
            $router->post('stopDevice', 'SessionsController@stopDevice');
            $router->get('{id}/delete', 'SessionsController@delete');
            $router->get('{id}/close', 'SessionsController@closeSession');

        });

        $app->group(['prefix' => 'devices/'], function ($router) {
            $router->post('index', 'DevicesController@index');
            $router->post('create', 'DevicesController@create');
            $router->get('{id}/show', 'DevicesController@show');
            $router->get('{id}/delete', 'DevicesController@delete');
            $router->post('{id}/update', 'DevicesController@update');
        });

        $app->group(['prefix' => 'foods/'], function ($router) {
            $router->post('index', 'FoodsController@index');
            $router->post('create', 'FoodsController@create');
            $router->get('{id}/show', 'FoodsController@show');
            $router->get('{id}/delete', 'FoodsController@delete');
            $router->post('{id}/update', 'FoodsController@update');
        });

        $app->group(['prefix' => 'employees/'], function ($router) {
            $router->post('index', 'EmployeesController@index');
            $router->post('create', 'EmployeesController@create');
            $router->get('{id}/show', 'EmployeesController@show');
            $router->get('{id}/delete', 'EmployeesController@delete');
            $router->post('{id}/update', 'EmployeesController@update');
        });
    });

}
