<?php

namespace App\Controllers;

use Gmo\Framework\Controller\AbstractController;
use Gmo\Framework\Http\Response;

class PostController extends AbstractController
{
    public function __construct() {}

    public function create(): Response
    {
        $a = 'asdasd12342354235trg';

        return $this->render('posts-create.twig', ['testParams' => $a]);
    }
}
