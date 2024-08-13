<?php

namespace Gmo\Framework\Tests;

class SomeClass
{
    public function __construct(private readonly InjClass $injClass) {}

    public function getInjClass(): InjClass
    {
        return $this->injClass;
    }
}
