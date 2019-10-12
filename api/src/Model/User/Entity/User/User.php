<?php
declare(strict_types=1);
namespace Api\Model\User\Entity\User;

use DateTimeImmutable;

class User
{
	const STATUS_WAIT = 'wait';
	const STATUS_ACTIVE = 'active';

	/**
	* @var UserId
	*/
	private $id;

	/**
	* @var DateTimeImmutable
	*/
	private $date;

	/**
	* @var Email
	*/
	private $email;

	/**
	* @var string
	*/
	private $passwordHash;

	/**
	* @var ConfirmToken
	*/
	private $confirmToken;

	/**
	* @var string
	*/
	private $status;


	public function __construct(UserId $id,DateTimeImmutable $date, Email $email, string $hash, ConfirmToken $confirmToken)
	{
		$this->id = $id;
		$this->date = $date;
		$this->email = $email;
		$this->hash = $hash;
		$this->confirmToken = $confirmToken;
		$this->status = self::STATUS_WAIT;
	}
	
}