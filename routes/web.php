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

$router->group(['prefix' => env('API_VERSION'), 'middleware' => 'externalAuth'], function ($route) {
    $route->get('/customer', 'CustomerController@findAll');
    $route->get('/customer/{id}', 'CustomerController@findOne');
    $route->post('/customer', 'CustomerController@create');
    $route->put('/customer/{id}', 'CustomerController@edit');
    $route->delete('/customer/{id}', 'CustomerController@destroy');
    $route->get('/customer-count', 'CustomerController@countData');
    
    $route->get('/users/{id}', 'UsersController@findOne');
    $route->get('/users', 'UsersController@findAll');
    $route->post('/users', 'UsersController@create');
    $route->put('/users/{id}', 'UsersController@edit');
    $route->delete('/users/{id}', 'UsersController@destroy');
    $route->get('/users-count', 'UsersController@countData');

    $route->get('/salesman/{id}', 'UsersController@findOne');
    $route->get('/salesman', 'UsersController@findAll');
    $route->post('/salesman', 'UsersController@create');
    $route->put('/salesman/{id}', 'UsersController@edit');
    $route->delete('/salesman/{id}', 'UsersController@destroy');
    $route->get('/salesman-count', 'UsersController@countData');
});