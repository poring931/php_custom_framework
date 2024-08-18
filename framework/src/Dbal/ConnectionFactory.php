<?php

namespace Gmo\Framework\Dbal;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;

readonly class ConnectionFactory
{
    public function __construct(
        private readonly string $databaseUrl
    ) {}

    public function create(): Connection
    {
        return DriverManager::getConnection([
            'url' => $this->databaseUrl,
            'driver' => 'pdo_mysql',
        ]);
    }
}
