<?php
declare(strict_types=1);
namespace Api\Model\User\Entity\User;

use Ramsey\Uuid\Uuid;
use Webmozart\Assert\Assert;

class UserId
{
	/**
	* @var string
	*/
	private $id;

	/**
	* @param string $id
	*/
	public function __construct(string $id)
	{
		Assert::notEmpty($id);
		$this->id = $id;
	}

	/**
	* @return string
	*/
	public function getId(): string
	{
		return $this->id;
	}

	/**
	* @return UserId
	*/
	public static function next(): self
	{
		return new self(Uuid::uuid4()->toString());
	}


	public function __toString(): string
    {
        return $this->id;
    }
	
}