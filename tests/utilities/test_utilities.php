<?php

function createRequest(
    $method,
    $content,
    $uri = '/test',
    $parameters = [],
    $server = ['CONTENT_TYPE' => 'application/json'],
    $cookies = [],
    $files = []
) {
    $request = new \Illuminate\Http\Request;
    return $request->createFromBase(
        \Symfony\Component\HttpFoundation\Request::create(
            $uri, $method, $parameters, $cookies, $files, $server, $content)
        );
}