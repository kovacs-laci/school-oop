<?php
use App\Routing\Router;

function getRouter() {
    $router = new Router();

    $router->add('GET', '/', 'App\Controllers\HomeController@index');
    $router->add('GET', '/about', 'App\Controllers\AboutController@index');
    $router->add('POST', '/submit', 'App\Controllers\FormController@submit');

    return $router;
}