<?php

declare(strict_types=1);

namespace ProjetNormandie\UserBundle\Tests\Functional\Api;

class LoginTest extends AbstractFunctionalTestCase
{
    public function testLoginOK(): void
    {
        $response = $this->apiClient->request('POST', '/api/login_check', ['json' => [
            'username' => 'sancho@local.fr',
            'password' => 'sancho',
        ]]);
        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/json');
        $arrayResponse = $response->toArray();
        $this->assertArrayHasKey('token', $arrayResponse);
        $this->assertNotEmpty($response->toArray()['token']);
    }

    public function testLoginNOK(): void
    {
        $response = $this->apiClient->request('POST', '/api/login_check', ['json' => [
            'username' => 'nobody@local.fr',
            'password' => 'nobody',
        ]]);
        $this->assertResponseStatusCodeSame(401);
        $this->assertResponseHeaderSame('content-type', 'application/json');
        $this->assertJsonEquals([
            'code' => 401,
            'message' => 'Invalid credentials.',
        ]);
        $this->assertJsonContains([
            'message' => 'Invalid credentials.',
        ]);
    }
}