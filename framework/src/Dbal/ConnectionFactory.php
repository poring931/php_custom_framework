<?php

namespace Gmo\Framework\Dbal;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;

readonly class ConnectionFactory
{
    public function __construct(
        private readonly array $connectionParams
    ) {}

    public function create(): Connection
    {
        return DriverManager::getConnection($this->connectionParams);
    }
}
