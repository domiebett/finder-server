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

$router->get("/", function () use ($router) {
    return $router->app->version();
});

// Routes for auth
$router->group(["prefix" => "api/v1/auth"], function($router) {
    $router->post("login", "V1\AuthController@login");
    $router->post("register", "V1\AuthController@register");
});

// Routes for items
$router->group(["prefix" => "api/v1"], function($router) {
    $router->get("items", "V1\ItemController@getItems");
    $router->post("items", "V1\ItemController@addItem");
});

// Routes for categories
$router->group(["prefix" => "api/v1"], function($router) {
    $router->get("categories", "V1\CategoryController@getCategories");
});
