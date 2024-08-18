<?php

namespace Gmo\Framework\Console;

use Gmo\Framework\Console\Exceptions\ConsoleException;
use Psr\Container\ContainerInterface;

class Application
{
    public function __construct(
        private ContainerInterface $container
    ) {}

    /**
     * @throws ConsoleException
     */
    public function run(): int
    {
        $argv = $_SERVER['argv'];

        $commandName = $argv[1] ?? null;
        if (! $commandName) {
            throw new ConsoleException('Invalid console command');
        }

        $args = array_slice($argv, 2);
        $options = $this->parseOptions($args);

        /* @var CommandInterface $command */
        $command = $this->container->get("console:$commandName");

        $status = $command->execute($options);

        return $status;
    }

    private function parseOptions(array $args): array
    {
        $options = [];
        foreach ($args as $arg) {
            if (str_starts_with($arg, '--')) {
                [$name, $value] = explode('=', $arg, 2);
                $options[substr($name, 2)] = $value ?? true;
            }
        }

        return $options;
    }
}
