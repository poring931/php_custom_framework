<?php

namespace Gmo\Framework\Console;

interface CommandInterface
{
    // docker-compose exec php php /var/www/html/console migrate
    public function execute(array $params = []): int;
}
