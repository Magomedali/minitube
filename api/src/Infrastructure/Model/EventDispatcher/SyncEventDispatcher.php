<?php
declare(strict_types=1);
namespace Api\Infrastructure\Model\EventDispatcher;

use Psr\Container\ContainerInterface;
use Api\Model\EventDispatcher;


class SyncEventDispatcher implements EventDispatcher
{

	/**
	* @var ContainerInterface
	*/
	private $container;

	/**
	* @var array
	*/
	private $listeners = [];

	public function __construct(ContainerInterface $container, array $listeners)
	{
		$this->container = $container;
		$this->listeners = $listeners;
	}


	public function dispatch(...$events): void
	{
		foreach ($events as $event) {
			$this->dispatchEvent($event);
		}
	}

	private function dispatchEvent($event): void
	{
		$eventName = \get_class($event);
		if(!array_key_exists($eventName, $this->listeners))
		{
			return;
		}

		foreach ($this->listeners[$eventName] as $listenerClass) {
			$listener = $this->resolveListener($listenerClass);
			$listener($event);
		}
	}


	private function resolveListener(string $listenerClass): callable
	{
		return $this->container->get($listenerClass);
	}

}