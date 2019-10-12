<?php
declare(strict_types=1);
namespace Api\Model\User\Entity\User;

use DateTimeImmutable;
use Webmozart\Assert\Assert;

class ConfirmToken
{
	/**
	* @var string
	*/
	private $token;

	/**
	* @var DateTimeImmutable
	*/
	private $expires;

	/**
	* @param string $token
	* @param DateTimeImmutable $expires
	*/
	public function __construct(string $token, DateTimeImmutable $expires)
	{
		Assert::notEmpty($token);

		$this->token = $token;
		$this->expires = $expires;
	}

	/**
	* @param DateTimeImmutable $date
	* @return boolean
	*/
	public function isExpiredTo(DateTimeImmutable $date): bool
	{
		return $this->expires <= $date;
	}
}