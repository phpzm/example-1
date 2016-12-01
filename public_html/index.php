<?php

use Projeto\Controller\DisciplinaController;

define('__APP_ROOT__', dirname(__DIR__));

require __APP_ROOT__ . '/vendor/autoload.php';

$disciplinaController = new DisciplinaController();

echo $disciplinaController->cadastrar();