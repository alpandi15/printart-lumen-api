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

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->get('/customer', 'CustomerController@findAll');
$router->get('/customer/{id}', 'CustomerController@findOne');
$router->post('/customer', 'CustomerController@create');
$router->put('/customer/{id}', 'CustomerController@edit');
$router->delete('/customer/{id}', 'CustomerController@destroy');