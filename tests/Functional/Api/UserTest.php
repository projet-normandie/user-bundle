<?php

namespace ProjetNormandie\UserBundle\Tests\Functional\Api;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use ProjetNormandie\UserBundle\Entity\User;

class UserTest extends ApiTestCase
{
    public function setUp(): void
    {
        self::bootKernel();
    }

    public function testGetCollection(): void
    {
        self::bootKernel();

        // The client implements Symfony HttpClient's `HttpClientInterface`, and the response `ResponseInterface`
        $response = static::createClient()->request('GET', '/api/users');

        $this->assertResponseIsSuccessful();
        // Asserts that the returned content type is JSON-LD (the default)
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');


        // Asserts that the returned JSON is validated by the JSON Schema generated for this resource by API Platform
        // This generated JSON Schema is also used in the OpenAPI spec!
        $this->assertMatchesResourceCollectionJsonSchema(User::class);
    }

    public function testCreateUser(): void
    {
        $response = static::createClient()->request('POST', '/api/users', [
            'json' => [
                'username' => 'tina',
                'plainPassword' => 'tina',
                'email' => 'tina@tina.fr',
            ]
        ]);
        $this->assertResponseStatusCodeSame(201);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertJsonContains([
            '@context' => '/api/contexts/User',
            '@type' => 'User',
            'username' => 'tina',
            'email' => 'tina@tina.fr',
        ]);
        $this->assertMatchesRegularExpression('~^/api/users/\d+$~', $response->toArray()['@id']);
        $this->assertMatchesResourceItemJsonSchema(
            User::class,
            null,
            'jsonld',
            ['groups' => ['user:read']]
        );

        /*$transport = static::getContainer()->get('messenger.transport.async');
        $this->assertCount(1, $transport->getSent());*/
    }
}
