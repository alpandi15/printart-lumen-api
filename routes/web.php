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

    $route->get('/salesman/{id}', 'SalesmanController@findOne');
    $route->get('/salesman', 'SalesmanController@findAll');
    $route->post('/salesman', 'SalesmanController@create');
    $route->put('/salesman/{id}', 'SalesmanController@edit');
    $route->delete('/salesman/{id}', 'SalesmanController@destroy');
    $route->get('/salesman-count', 'SalesmanController@countData');

    $route->get('/item/{id}', 'ItemController@findOne');
    $route->get('/item', 'ItemController@findAll');
    $route->get('/item-count', 'ItemController@countData');

    $route->get('/account/{id}', 'AccountController@findOne');
    $route->get('/account', 'AccountController@findAll');
    $route->post('/account', 'AccountController@create');
    $route->put('/account/{id}', 'AccountController@edit');
    $route->delete('/account/{id}', 'AccountController@destroy');
    $route->get('/account-count', 'AccountController@countData');
});