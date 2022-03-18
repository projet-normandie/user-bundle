<?php
namespace ProjetNormandie\UserBundle\Model;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use ProjetNormandie\UserBundle\Entity\User;

abstract class UserManager
{
    private UserPasswordHasherInterface $passwordHasher;

    /**
     * @param UserPasswordHasherInterface $passwordHasher
     */
    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    /**
     * @return User
     */
    public function createUser(): User
    {
        return new User();
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
     * @param User $user
     */
    public function updatePassword(User $user)
    {
         $user->setPassword($this->passwordHasher->hashPassword(
             $user,
             $user->getPlainPassword()
         ));
    }
}