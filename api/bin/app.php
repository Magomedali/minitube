<?php
declare(strict_types=1);

use Symfony\Component\Console\Application;
use Symfony\Component\Dotenv\Dotenv;
use Doctrine\Migrations\Tools\Console\Helper\ConfigurationHelper;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper;

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

if(file_exists('.env'))
{
	(new Dotenv())->load('.env');
}

/**
 * @var \Psr\Container\ContainerInterface $container
 */
$container = require 'config/container.php';

$cli = new Application('Application console');

$entityManager = $container->get(EntityManagerInterface::class);
$connection = $entityManager->getConnection();

/**
 * Configure migrations
 */
$configuration = new Doctrine\Migrations\Configuration\Configuration($connection);
$configuration->setMigrationsDirectory('src/Data/Migration');
$configuration->setMigrationsNamespace('Api\Data\Migration');

/**
 * Enable doctrine migration commands
 */
$cli->getHelperSet()->set(new ConfigurationHelper($connection, $configuration), 'configuration');
Doctrine\Migrations\Tools\Console\ConsoleRunner::addCommands($cli);

/**
 * Enable doctrine commands
 */
$cli->getHelperSet()->set(new EntityManagerHelper($entityManager), 'em');
Doctrine\ORM\Tools\Console\ConsoleRunner::addCommands($cli);

$commands = $container->get('config')['console']['commands'];
foreach ($commands as $command) {
	$cli->add($container->get($command));
}

$cli->run();