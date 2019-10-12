<?php
declare(strict_types=1);
namespace Test\Feature\Auth\SignUp;

use Test\Feature\DbWebTestCase;

class ConfirmTest extends DbWebTestCase
{

	public function setUp()
	{
		
        parent::setUp();

		$this->loadFixtures([
            'confirm'=>ConfirmFixture::class
        ]);
	}


	public function testMethod()
	{
		$response = $this->get('/auth/signup/confirm');
        self::assertEquals(405, $response->getStatusCode());
	}


	public function testSuccess()
	{
		$user = $this->getFixture('confirm')->getUser();
		$response = $this->post('/auth/signup/confirm',[
			'email'=>$user->getEmail()->getEmail(),
			'token'=>$user->getConfirmToken()->getToken()
		]);

        self::assertEquals(201, $response->getStatusCode());
        self::assertJson($content = $response->getBody()->getContents());

        $data = json_decode($content, true);
        
        self::assertEquals([], $data);
	}


	public function testNotFound()
	{
		$response = $this->post('/auth/signup/confirm',[
			'email'=>'not-found@email.ru',
			'token'=>'token'
		]);

        self::assertEquals(400, $response->getStatusCode());
        self::assertJson($content = $response->getBody()->getContents());

        $data = json_decode($content, true);
        
        self::assertEquals([
        	'error'=>'User is not found.'
        ], $data);
	}


	public function testAlreadyConfirmed()
	{
		$user = $this->getFixture('confirm')->getUser();
		$this->post('/auth/signup/confirm',[
			'email'=>$user->getEmail()->getEmail(),
			'token'=>$user->getConfirmToken()->getToken()
		]);

		$response = $this->post('/auth/signup/confirm',[
			'email'=>$user->getEmail()->getEmail(),
			'token'=>$user->getConfirmToken()->getToken()
		]);
        self::assertEquals(400, $response->getStatusCode());
        self::assertJson($content = $response->getBody()->getContents());

        $data = json_decode($content, true);
        
        self::assertEquals([
        	'error'=>'User is already active.'
        ], $data);
	}



	public function testInvalidToken()
	{
		$user = $this->getFixture('confirm')->getUser();
		$response = $this->post('/auth/signup/confirm',[
			'email'=>$user->getEmail()->getEmail(),
			'token'=>'invalid'
		]);

		self::assertEquals(400, $response->getStatusCode());
		self::assertJson($content = $response->getBody()->getContents());

		$data = json_decode($content, true);
		
		self::assertEquals([
			'error'=>'Confirm token is invalid.'
		], $data);

	}



	public function testExpiredToken()
	{
		$expired = $this->getFixture('confirm')->getExpired();
		$response = $this->post('/auth/signup/confirm',[
			'email'=>$expired->getEmail()->getEmail(),
			'token'=>$expired->getConfirmToken()->getToken()
		]);

		self::assertEquals(400, $response->getStatusCode());
		self::assertJson($content = $response->getBody()->getContents());

		$data = json_decode($content, true);
		
		self::assertEquals([
			'error'=>'Confirm token is expired.'
		], $data);

	}

}