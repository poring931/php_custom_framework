<?php

use App\Controllers\HomeController;
use App\Controllers\PostController;
use Gmo\Framework\Routing\Route;

return [
    Route::get('/', [HomeController::class, 'index']),
    Route::get('/posts/create', [PostController::class, 'create']),
    Route::get('/test', function () {
        return new \Gmo\Framework\Http\Response('test');
    }),
];
