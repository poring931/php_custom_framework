<?php

namespace Gmo\Framework\Http;

use Gmo\Framework\Http\Exceptions\HttpException;
use Gmo\Framework\Routing\RouterInterface;
use League\Container\Container;
use Throwable;

class Kernel
{
    private string $appEnv = 'local';

    public function __construct(
        private RouterInterface $router,
        private Container $container
    ) {
        $this->appEnv = $container->get('APP_ENV');
    }

    /**
     * @throws \Exception
     */
    public function handle(Request $request): Response
    {
        try {
            [$routeHandler, $vars] = $this->router->dispatch($request, $this->container);
            $response = call_user_func($routeHandler, $vars);
        } catch (Throwable $e) {
            $response = $this->createExceptionResponse($e);
        }

        return $response;

    }

    /**
     * @throws \Exception
     */
    private function createExceptionResponse(\Exception $e): Response
    {
        if (in_array($this->appEnv, ['local', 'testing'])) {
            throw $e;
        }
        if ($e instanceof HttpException) {
            return new Response($e->getMessage(), $e->getStatusCode());
        }

        return new Response('server error', 500);
    }
}
