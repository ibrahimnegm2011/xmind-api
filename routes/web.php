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

    $router->group(['prefix' => 'api/'], function ($app) {
        $app->post('login/','Authentication\LoginController@login');


        $app->group(['middleware' => 'auth'], function ($appAuth) {
            $appAuth->get('load/user', 'LoginController@loadUser');

            $appAuth->group(['prefix' => 'shifts/'], function ($router) {
                $router->post('index', 'ShiftsController@index');
//                $router->post('create', 'SubscriptionPlansController@create');
//                $router->post('{id}/update', 'SubscriptionPlansController@update');
//                $router->get('{id}/show', 'SubscriptionPlansController@show');
//                $router->get('{id}/delete', 'SubscriptionPlansController@delete');
            });

        });
    });
}
