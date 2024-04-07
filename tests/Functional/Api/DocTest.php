<?php

namespace ProjetNormandie\UserBundle\Tests\Functional\Api;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;

class DocTest extends ApiTestCase
{
    public function testDocSuccessfull(): void
    {
        // The client implements Symfony HttpClient's `HttpClientInterface`, and the response `ResponseInterface`
        $response = static::createClient()->request('GET', '/api/docs');

        $this->assertResponseIsSuccessful();
    }
}
