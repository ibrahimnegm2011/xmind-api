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
    $router->group(['prefix' => 'api/admin', 'middleware' => 'admin_auth'], function ($app) {

        $app->group(['prefix' => 'users/'], function ($router) {
            $router->post('index', 'UsersController@index');
            $router->post('create', 'UsersController@create');
            $router->get('{id}/show', 'UsersController@show');
            $router->get('{id}/delete', 'UsersController@delete');
            $router->post('{id}/update', 'UsersController@update');
        });

        $app->group(['prefix' => 'plans/'], function ($router) {
            $router->post('index', 'SubscriptionPlansController@index');
            $router->post('create', 'SubscriptionPlansController@create');
            $router->post('{id}/update', 'SubscriptionPlansController@update');
            $router->get('{id}/show', 'SubscriptionPlansController@show');
            $router->get('{id}/delete', 'SubscriptionPlansController@delete');
        });
    });
}
