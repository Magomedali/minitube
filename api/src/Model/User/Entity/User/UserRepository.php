<?php
declare(strict_types=1);
namespace Api\Model\User\Entity\User;


interface UserRepository
{
	/**
	* @param Email $email
	* @return boolean
	*/
	public function hasByEmail(Email $email): bool;


	/**
	* @param Email $email
	* @return User
	* @throws NotFoundException
	*/
	public function getByEmail(Email $email): User;


	/**
	* @param User $user
	* @return void
	*/
	public function add(User $user): void;
} 