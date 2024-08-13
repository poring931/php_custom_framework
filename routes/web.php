<?php

use App\Controllers\HomeController;
use Gmo\Framework\Routing\Route;

return [
    Route::get('/', [HomeController::class, 'index']),
    Route::get('/test', function () {
        return new \Gmo\Framework\Http\Response('test');
    }),
];
