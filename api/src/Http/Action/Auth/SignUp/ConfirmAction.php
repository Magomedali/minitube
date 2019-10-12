<?php
declare(strict_types=1);
namespace Api\Http\Action\Auth\SignUp;

use Api\Model\User\UseCase\SignUp\Confirm\Command;
use Api\Model\User\UseCase\SignUp\Confirm\Handler;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;
use Api\Http\Validator\Validator;
use DomainException;

class ConfirmAction implements RequestHandlerInterface
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
        

        if($errors = $this->validator->validate($command))
        {
            return new JsonResponse(['errors' => $errors->toArray()], 400);
        }
        
        $this->handler->handle($command);

        return new JsonResponse([], 201);
    }


    private function deserialize(ServerRequestInterface $request): Command
    {
    	$body = json_decode($request->getBody()->getContents(), true);
        $command = new Command();
        $command->email = $body['email'] ?? '';
        $command->token = $body['token'] ?? '';

        return $command;
    }
}