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

class RequestFixture extends AbstractFixture
{

	private $user;
	
	public function load(ObjectManager $manager)
	{
		
		$user = new User(
			UserId::next(),
			$now = new DateTimeImmutable(),
			new Email('test-mail@example.com'),
			'password_hash',
			new ConfirmToken($token = 'token', new DateTimeImmutable('+1 day'))
		);

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