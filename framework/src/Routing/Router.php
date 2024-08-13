<?php

namespace Gmo\Framework\Routing;

use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use Gmo\Framework\Http\Exceptions\MethodNotAllowedException;
use Gmo\Framework\Http\Exceptions\RouteNotFoundException;
use Gmo\Framework\Http\Request;

use function FastRoute\simpleDispatcher;

class Router implements RouterInterface
{
    public function dispatch(Request $request): array
    {
        [$handler, $vars] = $this->extractRouteInfo($request);
        if (is_array($handler)) {
            [$controller, $method] = $handler;
            $handler = [new $controller, $method];
        }

        return [
            $handler,
            $vars,
        ];
    }

    /**
     * @throws MethodNotAllowedException
     * @throws RouteNotFoundException
     */
    private function extractRouteInfo(Request $request): array
    {
        $dispatcher = simpleDispatcher(function (RouteCollector $collector) {

            $routes = include BASE_PATH.'/routes/web.php';
            foreach ($routes as $route) {
                $collector->addRoute(...$route);
            }

        });
        $routeInfo = $dispatcher->dispatch(
            $request->getMethod(),
            $request->getPath()
        );

        switch ($routeInfo[0]) {
            case Dispatcher::FOUND:
                return [$routeInfo[1], $routeInfo[2]];
            case Dispatcher::METHOD_NOT_ALLOWED :
                $exception = new MethodNotAllowedException(message: 'Supported HTTP methods: '.implode(', ', $routeInfo[1]));
                $exception->setStatusCode(405);
                throw $exception;
            default:
                $exception = new RouteNotFoundException(message: 'Route not found');
                $exception->setStatusCode(404);
                throw $exception;
        }
    }
}
