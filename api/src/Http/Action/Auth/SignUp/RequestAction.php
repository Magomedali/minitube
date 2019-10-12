<?php
declare(strict_types=1);
namespace Api\Http\Action\Auth\SignUp;

use Api\Model\User\UseCase\SignUp\Request\Command;
use Api\Model\User\UseCase\SignUp\Request\Handler;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;
use Api\Http\Validator\Validator;
use Api\Http\ValidationException;
use DomainException;

class RequestAction implements RequestHandlerInterface
{
    private $handler;

    private $validator;

    public function __construct(Handler $handler, Validator $validator)
    {
        $this->handler = $handler;
        $this->validator = $validator;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $command = $this->deserialize($request);
        
        $violations = $this->validator->validate($command);

        if($errors = $this->validator->validate($command))
        {
            throw new ValidationException($errors);
        }

        $this->handler->handle($command);

        return new JsonResponse(['email' => $command->email], 201);
    }


    private function deserialize(ServerRequestInterface $request): Command
    {
    	$body = json_decode($request->getBody()->getContents(), true);
        $command = new Command();
        $command->email = $body['email'] ?? '';
        $command->password = $body['password'] ?? '';

        return $command;
    }
}