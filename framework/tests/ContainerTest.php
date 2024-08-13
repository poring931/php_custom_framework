<?php

namespace Gmo\Framework\Tests;

use Gmo\Framework\Container\Container;
use Gmo\Framework\Container\Exceptions\ContainerException;
use PHPUnit\Framework\TestCase;

class ContainerTest extends TestCase
{
    public function test_getting_service_from_container()
    {
        $container = new Container;

        $container->add('some-class', concrete: SomeClass::class);

        $this->assertInstanceOf(SomeClass::class, $container->get('some-class'));

    }

    public function test_container_has_exception_ContainerException_if_add_wrong_service()
    {
        $container = new Container;

        $this->expectException(ContainerException::class);

        $container->add('some-class');
    }

    public function test_container_has_method()
    {
        $container = new Container;

        $container->add('some-class', concrete: SomeClass::class);
        $this->assertTrue($container->has('some-class'));
        $this->assertFalse($container->has('s2ome-class'));
    }

    public function test_recursive_autowiring()
    {
        $container = new Container;
        /* @var SomeClass $someClass */
        $container->add('some-class', SomeClass::class);
        $someClass = $container->get('some-class');
        $injectClass = $someClass->getInjClass();
        $this->assertInstanceOf(expected: InjClass::class, actual: $someClass->getInjClass());
        $this->assertInstanceOf(expected: InjClassSec::class, actual: $injectClass->getInjClassces());
        $this->assertInstanceOf(expected: InjClassThird::class, actual: $injectClass->getInjClassThird());
    }
}
