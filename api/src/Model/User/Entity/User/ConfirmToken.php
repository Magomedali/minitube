<?php
declare(strict_types=1);
namespace Api\Model\User\Entity\User;

use DateTimeImmutable;
use DomainException;
use Webmozart\Assert\Assert;
use Doctrine\ORM\Mapping as ORM;

/**
* @ORM\Embeddable
*/
class ConfirmToken
{
	/**
	* @var string
	* @ORM\Column(type="string", nullable=true)
	*/
	private $token;

	/**
	* @var DateTimeImmutable
	* @ORM\Column(type="datetime_immutable", nullable=true)
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
	* @param string $token
	* @param DateTimeImmutable $date
	* @return void
	* @throws DomainException
	*/
	public function validate(string $token,DateTimeImmutable $date): void
	{
		if(!$this->isEqualTo($token))
		{
			throw new DomainException('Confirm token is invalid.');
		}

		if($this->isExpiredTo($date))
		{
			throw new DomainException('Confirm token is expired.');
		}
	}

	/**
	* @return boolean
	*/
	public function isEmpty(): bool
    {
        return empty($this->token);
    }


	/**
	* @param string $token
	* @return boolean
	*/
	public function isEqualTo(string $token): bool
	{
		return $this->token === $token;
	}


	/**
	* @param DateTimeImmutable $date
	* @return boolean
	*/
	public function isExpiredTo(DateTimeImmutable $date): bool
	{
		return $this->expires <= $date;
	}

	/**
	* @return string
	*/
	public function getToken(): string
	{
		return $this->token;
	}
}