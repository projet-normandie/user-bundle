<?php

namespace ProjetNormandie\UserBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use ProjetNormandie\UserBundle\Entity\User;

class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * @param $q
     * @return mixed
     */
    public function autocomplete($q): mixed
    {
        $query = $this->createQueryBuilder('u');

        $query
            ->where('u.username LIKE :q')
            ->setParameter('q', '%' . $q . '%')
            //->andWhere('u.enabled = 1')
            ->orderBy('u.username', 'ASC');

        return $query->getQuery()->getResult();
    }

    /**
     * @param $object
     * @return void
     */
    public function save($object): void
    {
        $this->_em->persist($object);
        $this->_em->flush();
    }


    public function flush(): void
    {
        $this->_em->flush();
    }
}
