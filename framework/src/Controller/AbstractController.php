<?php

namespace Gmo\Framework\Controller;

use Gmo\Framework\Http\Request;
use Gmo\Framework\Http\Response;
use Psr\Container\ContainerInterface;

abstract class AbstractController
{
    protected ?ContainerInterface $container = null;

    protected Request $request;

    public function setContainer(ContainerInterface $container): void
    {
        $this->container = $container;
    }

    public function setRequest(Request $request): void
    {
        $this->request = $request;
    }

    public function render(string $view, array $parameters = [], ?Response $response = null): Response
    {
        $content = $this->container->get('twig')->render($view, $parameters);
        $response ??= new Response($content);

        $response->setContent($content);

        return $response;
    }
}
