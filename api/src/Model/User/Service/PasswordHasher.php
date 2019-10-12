<?php
declare(strict_types=1);
namespace Api\Model\User\Service;

interface PasswordHasher
{
	/**
	* @param string $password
	* @return string $hash
	*/
    public function hash(string $password): string;

    /**
	* @param string $password
	* @param string $hash
	* @return boolean
	*/
    public function validate(string $password, string $hash): bool;
}