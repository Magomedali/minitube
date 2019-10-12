<?php
declare(strict_types=1);
namespace Api\Infrastructure\Model\User\Service;

use DateInterval;
use DateTimeImmutable;
use Api\Model\User\Service\ConfirmTokenizer;
use Api\Model\User\Entity\User\ConfirmToken;

class RandConfirmTokenizer implements ConfirmTokenizer
{
	/**
	* @var string
	*/
	private $interval;
    

    public function __construct(DateInterval $interval)
    {
        $this->interval = $interval;
    }


   /**
	* @return ConfirmToken
	*/
	public function generate(): ConfirmToken
    {
        return new ConfirmToken(
            (string)random_int(100000, 999999),
            (new DateTimeImmutable())->add($this->interval)
        );
    }
}