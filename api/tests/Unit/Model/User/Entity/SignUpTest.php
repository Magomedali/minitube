<?php
declare(strict_types=1);
namespace Api\Test\Unit\Model\User\Entity\User;

use PHPUnit\Framework\TestCase;
use Test\Builder\User\UserBuilder;
use Api\Model\User\Entity\User\ConfirmToken;
use Api\Model\User\Entity\User\Email;
use Api\Model\User\Entity\User\User;
use Api\Model\User\Entity\User\UserId;


class SignUpTest extends TestCase
{
    public function testSuccess(): void
    {

        $user = UserBuilder::instance()
                                ->withId($id = UserId::next())
                                ->withDate($date = new \DateTimeImmutable())
                                ->withEmail($email = new Email('mail@example.com'))
                                ->withHash($hash = 'hash')
                                ->withConfirmToken($token = new ConfirmToken('token', new \DateTimeImmutable('+1 day')))
                                ->build();
        
        self::assertTrue($user->isWait());
        self::assertFalse($user->isActive());
        self::assertEquals($id, $user->getId());
        self::assertEquals($date, $user->getDate());
        self::assertEquals($email, $user->getEmail());
        self::assertEquals($hash, $user->getPasswordHash());
        self::assertEquals($token, $user->getConfirmToken());
    }
}