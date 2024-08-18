<?php

define('BASE_PATH', dirname(__DIR__));

require_once BASE_PATH . '/vendor/autoload.php';

use Gmo\Framework\Http\Kernel;
use Gmo\Framework\Http\Request;

$request = Request::createFromGlobals();

$container = require BASE_PATH . '/config/services.php';
$kernel = $container->get(Kernel::class);

$response = $kernel->handle($request);
$response->send();
