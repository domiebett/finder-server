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

$api = app('Dingo\Api\Routing\Router');

$api->version("v1", [
    "middleware" => "api.throttle",
    "limit" => 100,
    "expires" => 5,
    "prefix" => "api/v1"
], function ($api)
{

    $api->post("auth/login", "App\Http\Controllers\V1\AuthController@login");
    $api->post("auth/register", "App\Http\Controllers\V1\AuthController@register");
    $api->resource("items", "App\Http\Controllers\V1\ItemController",
        $except=["update", "destory", "show"]);
    $api->resource("categories", "App\Http\Controllers\V1\CategoryController",
        $except=["store", "show", "update", "destroy"]);
});

$router->get("/", function () use ($router) {
    return $router->app->version();
});
