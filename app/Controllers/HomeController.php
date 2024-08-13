<?php

namespace App\Controllers;

use Gmo\Framework\Http\Response;

class HomeController
{
    public function index(): Response
    {
        $content = '<h1>Hello, Worlds!</h1>';

        return new Response($content);
    }
}
