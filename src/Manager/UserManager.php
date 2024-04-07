<?php

namespace ProjetNormandie\UserBundle\Manager;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use ProjetNormandie\UserBundle\Entity\User;

class UserManager
{
    /**
     * Constructor.
     */
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    /**
     * @param User $user
     */
    public function deleteUser(User $user): void
    {
        $this->em->remove($user);
        $this->em->flush();
    }

    /**
     * @param User $user
     */
    public function updateUser(User $user): void
    {
        $this->em->persist($user);
        $this->em->flush();
    }

    /**
     * @param string $email
     * @return User|null
     */
    public function findUserByEmail(string $email): ?User
    {
        return $this->findUserBy(['email' => $email]);
    }

    /**
     * @param string $username
     * @return User|null
     */
    public function findUserByUsername(string $username): ?User
    {
        return $this->findUserBy(['username' => $username]);
    }

    /**
     * @param string $usernameOrEmail
     * @return User|null
     */
    public function findUserByUsernameOrEmail(string $usernameOrEmail): ?User
    {
        if (preg_match('/^.+\@\S+\.\S+$/', $usernameOrEmail)) {
            $user = $this->findUserByEmail($usernameOrEmail);
            if (null !== $user) {
                return $user;
            }
        }
        return $this->findUserByUsername($usernameOrEmail);
    }

    /**
     * @param string $token
     * @return User|null
     */
    public function findUserByConfirmationToken(string $token): ?User
    {
        return $this->findUserBy(['confirmationToken' => $token]);
    }


    /**
     * @param array $criteria
     * @return mixed
     */
    private function findUserBy(array $criteria): mixed
    {
        return $this->getRepository()->findOneBy($criteria);
    }

    /**
     * @return ObjectRepository
     */
    protected function getRepository(): ObjectRepository
    {
        return $this->em->getRepository('ProjetNormandie\UserBundle\Entity\User');
    }
}
