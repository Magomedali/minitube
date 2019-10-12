<?php
declare(strict_types=1);
namespace Api\Infrastructure\Model\Service;

use Doctrine\ORM\EntityManagerInterface;
use Api\Model\Flusher;


class DoctrineFlusher implements Flusher
{
	/**
	* @var EntityManagerInterface
	*/
	private $em;

	public function __construct(EntityManagerInterface $em)
	{
		$this->em = $em;
	}

	public function flush(): void
	{
		$this->em->flush();
	}
}