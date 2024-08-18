<?php

namespace Gmo\Framework\Console\Commands;

use Gmo\Framework\Console\CommandInterface;

class MigrateCommand implements CommandInterface
{
    private string $name = 'migrate';

    public function execute(array $params = []): int
    {
        echo "Migrating...\n";
        dd($params);

        return 0;
    }
}
