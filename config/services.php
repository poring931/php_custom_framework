<?php

use Doctrine\DBAL\Connection;
use Gmo\Framework\Console\Application;
use Gmo\Framework\Console\Commands\MigrateCommand;
use Gmo\Framework\Console\Kernel as ConsoleKernel;
use Gmo\Framework\Controller\AbstractController;
use Gmo\Framework\Dbal\ConnectionFactory;
use Gmo\Framework\Http\Kernel;
use Gmo\Framework\Routing\Router;
use Gmo\Framework\Routing\RouterInterface;
use League\Container\Argument\Literal\ArrayArgument;
use League\Container\Argument\Literal\StringArgument;
use League\Container\Container;
use League\Container\ReflectionContainer;
use Symfony\Component\Dotenv\Dotenv;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

$dsn = 'mysql:host=mysql;port=3306;dbname=testdb;charset=utf8mb4';
$username = 'user';
$password = 'user_password';

$dotenv = new Dotenv;
$dotenv->load(BASE_PATH . '/.env');

$routes = include BASE_PATH . '/routes/web.php';
$appEnv = $_ENV['APP_ENV'] ?? 'local';
$viewPath = BASE_PATH . '/views';

//$databaseConnectionParams = $_ENV['DATABASE_URL'] ?? 'mysql://user:user_password@mysql:3306/testdb?charset=utf8mb4';
$databaseConnectionParams = [
    'dbname' => 'testdb',
    'user' => 'user',
    'password' => 'user_password',
    'host' => 'mysql',
    'driver' => 'pdo_mysql',
];

$container = new Container;

$container->delegate(new ReflectionContainer(true));

$container->add('framework-commands-namespace', new StringArgument('Gmo\\Framework\\Console\\Commands\\'));

$container->add('APP_ENV', new StringArgument($appEnv));

$container->add(RouterInterface::class, Router::class);

$container->extend(RouterInterface::class)
    ->addMethodCall('registerRoutes', [new ArrayArgument($routes)]);

$container->add(Kernel::class)
    ->addArgument(RouterInterface::class)
    ->addArgument($container);

$container->addShared('twig-loader', FilesystemLoader::class)
    ->addArgument(new StringArgument($viewPath));

$container->addShared('twig', Environment::class)
    ->addArgument('twig-loader');

$container->inflector(AbstractController::class)
    ->invokeMethod('setContainer', [$container]);

$container->add(ConnectionFactory::class)
    ->addArgument(new ArrayArgument($databaseConnectionParams));

$container->addShared(Connection::class, function () use ($container): Connection {
    return $container->get(ConnectionFactory::class)->create();
});
$container->add(Application::class)->addArgument($container);

$container->add(ConsoleKernel::class)
    ->addArgument($container)
    ->addArgument(Application::class);

$container->add('console:migrate', MigrateCommand::class)
    ->addArgument(Connection::class)
    ->addArgument(new StringArgument(BASE_PATH . '/database/migrations'));

return $container;
