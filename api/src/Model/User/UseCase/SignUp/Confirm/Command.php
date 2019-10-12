<?php
declare(strict_types=1);
namespace Api\Model\User\UseCase\SignUp\Confirm;

class Command
{	
	/**
	* @var email
	*/
    public $email;

    /**
    * @var string
    */
    public $token;
}