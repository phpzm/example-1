<?php

define('__APP_ROOT__', dirname(__DIR__));

require __APP_ROOT__ . '/vendor/autoload.php';

use Projeto\Core\App;
use Stringy\Stringy;

$app = new App();

$app
    ->get('/controller/(\w+)/(\w+)', function ($classe, $metodo) {

        $controller = Stringy::create($classe)->append('Controller')->upperCamelize();

        $use = "\\Projeto\\Controller\\{$controller}";

        $instancia = new $use();

        return $instancia->$metodo();
    })
    ->get('/(.*)', function ($uri) {

        return $uri;
    });

echo $app($app->method(), $app->uri());