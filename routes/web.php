<?php

$router->get('/', function () use ($router) {
    return redirect('/users');
});

$router->get('/users', 'UsersController@index');
$router->get('/users/{id}', 'UsersController@show');
$router->post('/users', 'UsersController@store');
$router->put('/users/{id}', 'UsersController@update');
$router->patch('/users/{id}', 'UsersController@update');
$router->delete('/users/{id}', 'UsersController@destroy');
