<?php

declare(strict_types=1);

namespace ProjetNormandie\UserBundle\DataFixtures;

use Doctrine\ORM\Mapping\ClassMetadataInfo;
use ProjetNormandie\UserBundle\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    /**
     * @var array<string>
     */
    private array $entities = [
         'User'
    ];


    /**
     * @var array<mixed>
     */
    private array $users = [
        // USER 1
        [
            'id' => 1,
            'email' => 'sancho@local.fr',
            'username' => 'sancho',
            'plainPassword' => 'sancho',
            'roles' => ['ROLE_SUPER_ADMIN'],
        ],
        // USER 2
        [
            'id' => 2,
            'email' => 'nuts@local.fr',
            'username' => 'nuts',
            'plainPassword' => 'nuts',
            'roles' => [],
        ],
    ];

    private function updateGeneratorType(ObjectManager $manager): void
    {
        foreach ($this->entities as $entity) {
            $metadata = $manager->getClassMetaData("ProjetNormandie\\UserBundle\\Entity\\" . $entity);
            $metadata->setIdGeneratorType(ClassMetadataInfo::GENERATOR_TYPE_NONE);
        }
    }

    public function load(ObjectManager $manager): void
    {
        $this->updateGeneratorType($manager);
        $this->loadUsers($manager);
        $manager->flush();
    }


    /**
     * @param ObjectManager $manager
     * @return void
     */
    private function loadUsers(ObjectManager $manager): void
    {
        foreach ($this->users as $row) {
            $user = new User();
            $user->setId($row['id']);
            $user->setEmail($row['email']);
            $user->setUsername($row['username']);
            $user->setPlainPassword($row['plainPassword']);
            $user->setRoles($row['roles']);
            $manager->persist($user);
        }
    }
}
