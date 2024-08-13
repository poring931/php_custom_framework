<?php

define('BASE_PATH', dirname(__DIR__));

require_once BASE_PATH.'/vendor/autoload.php';

use Gmo\Framework\Http\Kernel;
use Gmo\Framework\Http\Request;
use Gmo\Framework\Routing\Router;

$router = new Router;
$request = Request::createFromGlobals();
$kernel = new Kernel($router);

$response = $kernel->handle($request);

$response->send();
