<?php
declare(strict_types=1);
namespace Test\Feature;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\App;
use Zend\Diactoros\Stream;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequest;
use Zend\Diactoros\Uri

class WebTestCase extends TestCase
{

	protected function get(string $uri,array $headers = []): ResponseInterface
	{
		return $this->method('GET', $uri, [], $headers);
	}


	protected function method(string $method, string $uri, array $params, array $headers): ResponseInterface
	{
		$body = new Stream('php://temp','r+');
		$body->write(json_encode($params));
		$body->rewind();

		$request = (new ServerRequest())
							->withHeader('Content-type','application/json')
							->withHeader('Accept','application/json')
							->withMethod($method)
							->withBody($body)
							->withUri(new Uri('http://test'.$uri));
							


		foreach ($headers as $name => $value) {
			$request->withHeader($name,$value);
		}

		return $this->request($request);
	}



	protected function request(ServerRequestInterface $request): ResponseInterface
	{
		$app = $this->app();
		$response = $app->process($request,new Response());
		$response->withBody()->rewind();
		return $response;
	}


	protected function app(): App
	{
		$app = new App($this->getContainer());
		(require 'config/routes.php')($app);
		return $app;
	}


	protected function getContainer(): ContainerInterface
	{
		return require 'config/container.php';
	}
}