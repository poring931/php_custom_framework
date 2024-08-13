<?php

namespace Gmo\Framework\Tests;

class InjClass
{
    public function __construct(private readonly InjClassSec $injClassces, private readonly InjClassThird $injClassThird) {}

    public function getInjClassces(): InjClassSec
    {
        return $this->injClassces;
    }

    public function getInjClassThird(): InjClassThird
    {
        return $this->injClassThird;
    }
}
