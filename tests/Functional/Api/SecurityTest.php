<?php

declare(strict_types=1);

namespace ProjetNormandie\UserBundle\Tests\Functional\Api;

use ProjetNormandie\UserBundle\Entity\User;
use Datetime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Transport\InMemory\InMemoryTransport;

class SecurityTest extends AbstractFunctionalTestCase
{
    private string $token = 'gPlbM4ZJ1ZooU1G8DiTyIfEEY3grjwdgxYA56Scw3mj2tDVIajcFFVKClECZXvJ4';
    private string $password = 'password';

    public function testSendPasswordResetLink(): void
    {
        $response = $this->apiClient->request('POST', '/api/security/send-password-reset-link', ['json' => [
            'username' => 'sancho',
        ]]);
        $this->assertResponseIsSuccessful();

        /** @var User $user */
        $user = static::getContainer()->get('doctrine')->getRepository(User::class)
            ->findOneBy(['username' => 'sancho']);
        $this->assertNotNull($user->getConfirmationToken());
        $this->assertNotNull($user->getPasswordRequestedAt());

        /** @var InMemoryTransport $transport */
        $transport = $this->getContainer()->get('messenger.transport.async');
        $this->assertCount(1, $transport->get());
    }



    public function testConfirmPassword(): void
    {
        /** @var EntityManagerInterface $em */
        $em =  static::getContainer()->get('doctrine.orm.entity_manager');

        /** @var User $user */
        $user = $em->getRepository(User::class)->findOneBy(['email' => 'sancho@local.fr']);
        $user->setConfirmationToken($this->token);
        $user->setPasswordRequestedAt(new Datetime());
        $oldHashPassword = $user->getPassword();
        $em->flush();

        $response = $this->apiClient->request('POST', '/api/security/confirm-password', ['json' => [
            'token' => $this->token,
            'password' => $this->password,
        ]]);
        $this->assertResponseIsSuccessful();

        /** @var User $user */
        $user = $em->getRepository(User::class)->findOneBy(['email' => 'sancho@local.fr']);
        $newHashPassword = $user->getPassword();
        $this->assertNotEquals($oldHashPassword, $newHashPassword);
    }


    public function testPasswordRequestedMore24Hours(): void
    {
        $token = 'gPlbM4ZJ1ZooU1G8DiTyIfEEY3grjwdgxYA56Scw3mj2tDVIajcFFVKClECZXvJ4';
        $password = 'webaxys';

        /** @var EntityManagerInterface $em */
        $em =  static::getContainer()->get('doctrine.orm.entity_manager');

        /** @var User $user */
        $user = $em->getRepository(User::class)->findOneBy(['username' => 'sancho']);
        $user->setConfirmationToken($token);
        $date = new Datetime();
        $date->modify('-2 day');
        $user->setPasswordRequestedAt($date);
        $em->flush();


        $response = $this->apiClient->request('POST', '/api/security/confirm-password', ['json' => [
            'token' => $token,
            'password' => $password,
        ]]);
        $this->assertResponseStatusCodeSame(400);
    }
}
