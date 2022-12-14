<?php
namespace ProjetNormandie\UserBundle\Doctrine;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use ProjetNormandie\UserBundle\Entity\User;
use ProjetNormandie\UserBundle\Model\UserManager as BaseUserManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserManager extends BaseUserManager
{

    protected EntityManagerInterface $em;

    /**
     * Constructor.
     */
    public function __construct(EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher)
    {
        parent::__construct($passwordHasher);
        $this->em = $em;
    }

    /**
     * @param User $user
     */
    public function deleteUser(User $user)
    {
        $this->em->remove($user);
        $this->em->flush();
    }

    /**
     * @param array $criteria
     * @return mixed
     */
    public function findUserBy(array $criteria)
    {
        return $this->getRepository()->findOneBy($criteria);
    }

    /**
     * @return array|object[]
     */
    public function findUsers(): array
    {
        return $this->getRepository()->findAll();
    }

    /**
     * @param User $user
     */
    public function reloadUser(User $user)
    {
        $this->em->refresh($user);
    }

    /**
     * @param User $user
     * @param bool          $andFlush
     */
    public function updateUser(User $user, bool $andFlush = true)
    {
        if ($user->getPlainPassword() != null) {
            $this->updatePassword($user);
        }

        $this->em->persist($user);
        if ($andFlush) {
            $this->em->flush();
        }
    }

    /**
     * @return ObjectRepository
     */
    protected function getRepository(): ObjectRepository
    {
        return $this->em->getRepository('ProjetNormandie\UserBundle\Entity\User');
    }
}