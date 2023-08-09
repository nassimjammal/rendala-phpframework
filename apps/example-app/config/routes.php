<?php

$routes['main'] = [
    'url'           => '/',
    'controller'    => 'dashboard',
    'action'        => 'index',
    'methods'       => ['GET', 'HEAD'],

];

$routes['hello'] = [
    'url'           => '/hello/{name}',
    'controller'    => 'dashboard',
    'action'        => 'index',
    'methods'       => ['GET', 'HEAD'],
    'requirements'  => [
        'name' => '\s+'
    ],
    'default'       => [
        'name'  => 'World'
    ],
    'priority'      => 1,
    'format'        => 'html', // json,xml,etc...
    // 'locale'        => 'en', 
    'locales'       => [
        'fr'  => '/bonjour/{name}'
     ],
];

// include (__DIR__ . '/routes/_alias.php'); // https://symfony.com/doc/current/routing.html#route-aliasing