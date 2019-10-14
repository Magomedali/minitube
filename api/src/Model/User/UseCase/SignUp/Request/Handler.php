<?php
declare(strict_types=1);
namespace Api\Model\User\UseCase\SignUp\Request;

use DateTimeImmutable;
use DomainException;
use Api\Model\Flusher;
use Api\Model\EventDispatcher;
use Api\Model\User\Entity\User\UserRepository;
use Api\Model\User\Entity\User\User;
use Api\Model\User\Entity\User\Email;
use Api\Model\User\Entity\User\UserId;
use Api\Model\User\Service\ConfirmTokenizer;
use Api\Model\User\Service\PasswordHasher;

class Handler
{
	/**
	* @var UserRepository
	*/
    public $users;

    /**
	* @var PasswordHasher
	*/
    public $hasher;

    /**
	* @var ConfirmTokenizer
	*/
    public $tokenizer;

    /**
	* @var Flusher
	*/
    public $flusher;

    /**
    * @var EventDispatcher
    */
    public $dispatcher;

    /**
	* @param UserRepository
	* @param PasswordHasher
	* @param ConfirmTokenizer
	* @param Flusher
	*/
    public function __construct(UserRepository $users, PasswordHasher $hasher, ConfirmTokenizer $tokenizer, Flusher $flusher, EventDispatcher $dispatcher)
    {
    	$this->users = $users;
        $this->hasher = $hasher;
        $this->tokenizer = $tokenizer;
        $this->flusher = $flusher;
        $this->dispatcher = $dispatcher;
    }

    /**
    * @param Command $command
    * @return void
    */
    public function handle(Command $command): void
    {
    	$email = new Email($command->email);

    	if($this->users->hasByEmail($email))
    	{
    		throw new DomainException('User with this email already exists.');
    	}

    	$user = new User(
    		UserId::next(),
    		new DateTimeImmutable(),
    		$email,
    		$this->hasher->hash($command->password),
    		$this->tokenizer->generate()
    	);


    	$this->users->add($user);

    	$this->flusher->flush();
        
        $this->dispatcher->dispatch(...$user->releaseEvents());
    }
}