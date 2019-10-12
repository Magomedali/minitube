<?php
declare(strict_types=1);
namespace Test\Feature\Auth\SignUp;

use Test\Feature\DbWebTestCase;

class RequestTest extends DbWebTestCase
{

	public function setUp()
	{
		parent::setUp();

		$this->loadFixtures([
            'request'=>RequestFixture::class,
        ]);
	}


	public function testMethod()
	{
		$response = $this->get('/auth/signup');
        self::assertEquals(405, $response->getStatusCode());
	}


	public function testSuccess()
	{

		$response = $this->post('/auth/signup',[
			'email'=>'test-mail@example.com',
			'password'=>'test-password'
		]);

        self::assertEquals(201, $response->getStatusCode());
        self::assertJson($content = $response->getBody()->getContents());

        $data = json_decode($content, true);
        
        self::assertEquals([
            'email' => 'test-mail@example.com',
        ], $data);

	}


	public function testExisting()
	{

		$response = $this->post('/auth/signup',[
			'email'=>'test-mail@example.com',
			'password'=>'test-password'
		]);

		self::assertEquals(201, $response->getStatusCode());
		self::assertJson($content = $response->getBody()->getContents());

		$data = json_decode($content, true);
		
		self::assertEquals([
            'error' => 'User with this email already exists.',
        ], $data);

	}

}