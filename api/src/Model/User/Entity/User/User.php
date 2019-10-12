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

	/**
	* @param UserId $id
	* @param DateTimeImmutable $date
	* @param Email $email
	* @param string $hash
	* @param ConfirmToken $confirmToken
	*/
	public function __construct(UserId $id,DateTimeImmutable $date, Email $email, string $hash, ConfirmToken $confirmToken)
	{
		$this->id = $id;
		$this->date = $date;
		$this->email = $email;
		$this->passwordHash = $hash;
		$this->confirmToken = $confirmToken;
		$this->status = self::STATUS_WAIT;
	}
	

	/**
	* @return bool
	*/
	public function isWait(): bool
    {
        return $this->status === self::STATUS_WAIT;
    }

    /**
	* @return bool
	*/
    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    /**
	* @return UserId
	*/
    public function getId(): UserId
    {
        return $this->id;
    }

    /**
	* @return DateTimeImmutable
	*/
    public function getDate(): DateTimeImmutable
    {
        return $this->date;
    }

    /**
	* @return Email
	*/
    public function getEmail(): Email
    {
        return $this->email;
    }

    /**
	* @return string
	*/
    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }

    /**
	* @return ConfirmToken
	*/
    public function getConfirmToken(): ConfirmToken
    {
        return $this->confirmToken;
    }

}