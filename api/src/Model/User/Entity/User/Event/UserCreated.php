<?php
declare(strict_types=1);
namespace Api\Model\User\Entity\User\Event;

use Api\Model\User\Entity\User\ConfirmToken;
use Api\Model\User\Entity\User\Email;
use Api\Model\User\Entity\User\UserId;
class UserCreated
{

	/**
	* @var UserId
	*/
	public $id;

	/**
	* @var Email
	*/
    public $email;

    /**
	* @var ConfirmToken
	*/
    public $confirmToken;

    public function __construct(UserId $id, Email $email, ConfirmToken $confirmToken)
    {
        $this->id = $id;
        $this->email = $email;
        $this->confirmToken = $confirmToken;
    }
}