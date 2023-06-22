<?php

use Illuminate\Support\Facades\Route;


/** @var \Laravel\Lumen\Routing\Router $router */

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


$router->group(['prefix' => 'api'], function () use ($router) {
    $router->post('register','UserController@register');
    $router->post('login','UserController@login');
});


$router->group(['prefix' => 'api', 'middleware' => 'auth'], function () use ($router) {
    //category
    $router->get('get-categories/{id}', 'CategoryController@getCategory');
    $router->post('add-categories', 'CategoryController@store');
    $router->post('update-categories/{id}', 'CategoryController@update');
    $router->delete('delete-categories/{id}', 'CategoryController@delete');
    //book
    $router->get('get-book/{id}', 'BookController@getBook');
    $router->post('add-book', 'BookController@store');
    $router->post('update-book/{id}', 'BookController@update');
    $router->delete('delete-book/{id}', 'BookController@delete');
    //visitory
    $router->get('get-visitory/{id}', 'VisitoryController@getVisitory');
    $router->post('add-visitory', 'VisitoryController@store');
    $router->post('update-visitory/{id}', 'VisitoryController@update');
    $router->delete('delete-visitory/{id}', 'VisitoryController@delete');
    //delivery
    $router->post('check-book', 'DeliveryController@checkBook');
    $router->post('rent-book', 'DeliveryController@rentBook');
    $router->post('return-book/{bookId}', 'DeliveryController@returnBook');

});

