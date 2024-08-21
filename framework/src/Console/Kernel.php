<?php

namespace Gmo\Framework\Console;

use Psr\Container\ContainerInterface;

class Kernel
{
    // docker-compose exec php php /var/www/html/console migrate
    public function __construct(
        private ContainerInterface $container,
        private Application $application
    ) {}

    /**
     * @throws \Exception
     */
    public function handle(): int
    {
        $this->registerCommands();

        $status = $this->application->run();

        return $status;
    }

    private function registerCommands(): void
    {
        $commandFiles = new \DirectoryIterator(__DIR__ . '/Commands');
        $namespace = $this->container->get('framework-commands-namespace');

        foreach ($commandFiles as $commandFile) {
            if (! $commandFile->isFile()) {
                continue;
            }
            $command = $namespace . pathinfo($commandFile, PATHINFO_FILENAME);
            if (is_subclass_of($command, CommandInterface::class)) {
                $name = (new \ReflectionClass($command))
                    ->getProperty('name')
                    ->getDefaultValue();
                $this->container->add("console:$name", $command);
            }
        }
    }
}
