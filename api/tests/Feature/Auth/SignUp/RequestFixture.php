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

class RequestFixture extends AbstractFixture
{

	private $user;
	
	public function load(ObjectManager $manager)
	{
		
		$user = UserBuilder::instance()
								->withId(UserId::next())
								->withDate($now = new DateTimeImmutable())
								->withEmail(new Email('test-yandex@example.com'))
								->withHash('password_hash')
								->withConfirmToken(new ConfirmToken($token = 'token', new DateTimeImmutable('+1 day')))
								->build();

		$user->confirmSignup($token, $now);

		$this->user = $user;

		$manager->persist($user);
        $manager->flush();
	}

	public function getUser(): User
	{
		return $this->user;
	}
}