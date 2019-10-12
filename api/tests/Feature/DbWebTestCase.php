<?php
declare(strict_types=1);
namespace Test\Feature;

use InvalidArgumentException;
use Psr\Container\ContainerInterface;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;

class DbWebTestCase extends WebTestCase
{
	private $_fixtures = [];



	protected function loadFixtures(array $fixtures)
	{
		$container = $this->getContainer();
		$em = $container->get(EntityManagerInterface::class);
		$loader = new Loader();
		foreach ($fixtures as $name => $class) {
			
			if($container->has($class)){
				$fixture = $container->get($class);
			}else{
				$fixture = new $class();
			}
			$loader->addFixture($fixture);
			$this->$_fixtures[$name] = $fixture;
		}
		$executor = new ORMExecutor($em, new ORMPurger($em));
		$executor->execute($loader->getFixtures());
	}



	protected function getFixture(string $name)
	{
		if(!array_key_exists($name, $this->_fixtures))
		{
			throw new InvalidArgumentException("Invalid fixture name.");
		}

		return $this->_fixtures[$name];
	}
}