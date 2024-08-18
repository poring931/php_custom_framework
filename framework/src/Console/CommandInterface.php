<?php

namespace Gmo\Framework\Console;

interface CommandInterface
{
    public function execute(array $params = []): int;
}
