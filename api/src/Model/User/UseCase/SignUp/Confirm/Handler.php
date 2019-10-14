<?php
declare(strict_types=1);
namespace Api\Model\User\UseCase\SignUp\Confirm;

use DateTimeImmutable;
use Api\Model\Flusher;
use Api\Model\User\Entity\User\UserRepository;
use Api\Model\User\Entity\User\Email;

class Handler
{	
	/**
	* @var UserRepository
	*/
    public $users;

    /**
    * @var Flusher
    */
    public $flusher;


    /**
    * @param UserRepository $users
    * @param Flusher $flusher
    */
    public function __construct(UserRepository $users, Flusher $flusher)
    {
    	$this->users = $users;
        $this->flusher = $flusher;
    }


    /**
    * @param Command $command
    * @return void
    */
    public function handle(Command $command): void
    {
    	$user = $this->users->getByEmail(new Email($command->email));

    	$user->confirmSignup($command->token, new DateTimeImmutable());

    	$this->flusher->flush($user);
    }
}