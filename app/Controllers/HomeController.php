<?php

namespace App\Controllers;

use Gmo\Framework\Controller\AbstractController;
use Gmo\Framework\Http\Response;

class HomeController extends AbstractController
{
    public function __construct() {}

    public function index(): Response
    {
        $a = 'asdasd12342354235trg';

        return $this->render('home.html.twig', ['testParams' => $a]);
    }
}
