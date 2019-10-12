<?php
declare(strict_types=1);
namespace Test\Feature\Auth\SignUp;

use Api\Model\User\Entity\User\ConfirmToken;
use Api\Model\User\Entity\User\Email;
use Api\Model\User\Entity\User\User;
use Api\Model\User\Entity\User\UserId;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use DateTimeImmutable;

class ConfirmFixture extends AbstractFixture
{

	private $user;

	private $expired;

	
	public function load(ObjectManager $manager)
	{
		
		$this->user = new User(
			UserId::next(),
			$now = new DateTimeImmutable(),
			new Email('test-yandex@example.com'),
			'password_hash',
			new ConfirmToken('token', new DateTimeImmutable('+1 day'))
		);

		$manager->persist($this->user);


		$this->expired =  new User(
			UserId::next(),
			$now = new DateTimeImmutable(),
			new Email('test-expired@example.com'),
			'password_hash',
			new ConfirmToken('expired', new DateTimeImmutable('-1 day'))
		);

		$manager->persist($this->expired);
        $manager->flush();
	}

	public function getUser(): User
	{
		return $this->user;
	}

	public function getExpired(): User
	{
		return $this->expired;
	}
}