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
            'request'=>RequestFixture::class
        ]);
	}


	public function testMethod()
	{
		$response = $this->get('/auth/signup');
        self::assertEquals(405, $response->getStatusCode());
	}


	public function testNotValidData()
	{
		$response = $this->post('/auth/signup', [
            'email' => 'incorrect-mail',
            'password' => 'short',
        ]);
        
        self::assertEquals(400, $response->getStatusCode());
        self::assertJson($content = $response->getBody()->getContents());

        $data = json_decode($content, true);
        self::assertEquals([
            'errors' => [
                'email' => 'This value is not a valid email address.',
                'password' => 'This value is too short. It should have 6 characters or more.',
            ],
        ], $data);
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
		$user = $this->getFixture('request')->getUser();
		$response = $this->post('/auth/signup',[
			'email'=>$user->getEmail()->getEmail(),
			'password'=>'test-password'
		]);

		self::assertEquals(400, $response->getStatusCode());
		self::assertJson($content = $response->getBody()->getContents());

		$data = json_decode($content, true);
		
		self::assertEquals([
            'error' => 'User with this email already exists.',
        ], $data);

	}

}