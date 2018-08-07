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
    $router->post("signup", "V1\AuthController@signup");
});

// Routes for items
$router->group(["prefix" => "api/v1/items"], function($router) {
    $router->get("/", ["uses" => "V1\ItemController@index"]);
    $router->post("/", ["middleware" => "auth", "uses" => "V1\ItemController@store"]);
});

// Routes for categories
$router->group(["prefix" => "api/v1/categories"], function($router) {
    $router->get("/", "V1\CategoryController@index");
    $router->post("/", ["middleware" => "admin", "uses" => "V1\CategoryController@store"]);
});
