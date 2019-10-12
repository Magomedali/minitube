<?php
declare(strict_types=1);
namespace ApiInfrastructure\Model\User\Service;

use Api\Model\User\Service\PasswordHasher;

class BCryptPasswordHasher extends PasswordHasher
{
	/**
	* @var int
	*/
	private $cost;
    

    public function __construct(int $cost = 12)
    {
        $this->cost = $cost;
    }


    /**
	* @param string $password
	* @return string $hash
	*/
	public function hash(string $password): string
	{
		$hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => $this->cost]);

		if(!$hash)
		{
			throw new \RuntimeException('Unable to generate hash.');
		}

		return $hash;
	}


	/**
	* @param string $password
	* @param string $hash
	* @return boolean
	*/
	public function validate(string $password, string $hash): bool
	{
		return password_verify($password,$hash);
	}
}