<?php
declare(strict_types=1);
namespace Test\Feature\Auth\SignUp;

use Test\Builder\User\UserBuilder;
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
		
		$this->user = UserBuilder::instance()
								->withId(UserId::next())
								->withDate(new DateTimeImmutable())
								->withEmail(new Email('test-yandex@example.com'))
								->withHash('password_hash')
								->withConfirmToken(new ConfirmToken($token = 'token', new DateTimeImmutable('+1 day')))
								->build();

		$manager->persist($this->user);

		$this->expired = UserBuilder::instance()
								->withId(UserId::next())
								->withDate(new DateTimeImmutable())
								->withEmail(new Email('test-expired@example.com'))
								->withHash('password_hash')
								->withConfirmToken(new ConfirmToken('expired', new DateTimeImmutable('-1 day')))
								->build();

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