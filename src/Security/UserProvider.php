<?php

namespace ProjetNormandie\UserBundle\Security;

use ProjetNormandie\UserBundle\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;

final class UserProvider implements UserProviderInterface
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param string $identifier
     * @return User
     */
    public function loadUserByIdentifier(string $identifier): User
    {
        $user = $this->findUserByUsernameOrEmail($identifier);

        if (!$user) {
            throw new UserNotFoundException(
                sprintf(
                    'User with "%s" email does not exist.',
                    $identifier
                )
            );
        }

        return $user;
    }

    /**
     * @param $usernameOrEmail
     * @return User|null
     */
    public function findUserByUsernameOrEmail($usernameOrEmail): ?User
    {
        if (preg_match('/^.+\@\S+\.\S+$/', $usernameOrEmail)) {
            $user = $this->findOneUserBy(['email' => $usernameOrEmail]);
            if (null !== $user) {
                return $user;
            }
        }

        return $this->findOneUserBy(['username' => $usernameOrEmail]);
    }

    /**
     * @param array $options
     * @return User|null
     */
    private function findOneUserBy(array $options): ?User
    {
        return $this->entityManager
            ->getRepository(User::class)
            ->findOneBy($options);
    }

    /**
     * @param UserInterface $user
     * @return User
     */
    public function refreshUser(UserInterface $user): User
    {
        assert($user instanceof User);

        if (null === $reloadedUser = $this->findOneUserBy(['id' => $user->getId()])) {
            throw new UserNotFoundException(sprintf(
                'User with ID "%s" could not be reloaded.',
                $user->getId()
            ));
        }

        return $reloadedUser;
    }

    /**
     * @param string $class
     * @return bool
     */
    public function supportsClass(string $class): bool
    {
        return $class === User::class;
    }
}
