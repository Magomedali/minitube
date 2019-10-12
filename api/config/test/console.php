<?php
declare(strict_types=1);

use Api\Console\Command\Fixture;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;

return [
    Fixture\FixtureCommand::class => function (ContainerInterface $container) {
        return new Fixture\FixtureCommand(
            $container->get(EntityManagerInterface::class),
            'src/Data/Fixture'
        );
    },

    'config' => [
        'console' => [
            'commands' => [
                Fixture\FixtureCommand::class,
            ],
        ],
    ],
    
];