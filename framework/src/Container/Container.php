<?php

namespace Gmo\Framework\Container;

use Gmo\Framework\Container\Exceptions\ContainerException;
use Psr\Container\ContainerInterface;

class Container implements ContainerInterface
{
    private array $services = [];

    /**
     * @throws ContainerException
     */
    public function add(string $id, string|object|null $concrete = null): void
    {
        if ($concrete === null) {

            if (! class_exists($id)) {
                throw new ContainerException("Service $id does not exist");
            }
            $concrete = $id;
        }
        $this->services[$id] = $concrete;

    }

    public function get(string $id): object
    {
        if (! $this->has($id)) {
            if (! class_exists($id)) {

                throw new ContainerException("Service $id could not be resolved");
            }

            $this->add($id);
        }

        $instance = $this->resolve($this->services[$id]);

        return $instance;
    }

    private function resolve($class)
    {

        $reflectionClass = new \ReflectionClass($class);
        $constructor = $reflectionClass->getConstructor();

        if ($constructor === null) {
            return $reflectionClass->newInstance();
        }

        $constructorParameters = $constructor->getParameters();
        $classDependencies = $this->resolveClassDependencies($constructorParameters);

        $instance = $reflectionClass->newInstanceArgs($classDependencies);

        return $instance;
    }

    private function resolveClassDependencies(array $constructorParameters): array
    {

        $classDependencies = [];
        foreach ($constructorParameters as $parameter) {
            $serviceType = $parameter->getType();

            $service = $this->get($serviceType->getName());
            $classDependencies[] = $service;
        }

        return $classDependencies;
    }

    public function has(string $id): bool
    {

        return array_key_exists($id, $this->services);
    }
}
