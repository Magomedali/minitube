<?php
declare(strict_types=1);

use Api\Http\Action;
use Api\Http\Middleware;
use Api\Model;

use Psr\Container\ContainerInterface;

return [
	
	Middleware\DomainExceptionMiddleware::class => function () {
        return new Middleware\DomainExceptionMiddleware();
    },

    Middleware\ErrorHandlerMiddleware::class => function () {
        return new Middleware\ErrorHandlerMiddleware();
    },

    Middleware\BodyParamsMiddleware::class => function () {
        return new Middleware\BodyParamsMiddleware();
    },

    Action\HomeAction::class => function () {
        return new Action\HomeAction();
    },

    Action\Auth\SignUp\RequestAction::class => function(ContainerInterface $container) {
        return new Action\Auth\SignUp\RequestAction($container->get(Model\User\UseCase\SignUp\Request\Handler::class));
    }
];