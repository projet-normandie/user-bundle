<?php

namespace ProjetNormandie\UserBundle\Repository;

use Doctrine\Persistence\ManagerRegistry;
use ProjetNormandie\UserBundle\Entity\User;

class UserRepository extends DefaultRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * @param $q
     * @return mixed
     */
    public function autocomplete($q)
    {
        $query = $this->createQueryBuilder('u');

        $query
            ->where('u.username LIKE :q')
            ->setParameter('q', '%' . $q . '%')
            ->andWhere('u.enabled = 1')
            ->orderBy('u.username', 'ASC');

        return $query->getQuery()->getResult();
    }

    /**
     * @param $date
     * @return int|mixed|string
     */
    public function getLoggedToday($date)
    {
        $query = $this->createQueryBuilder('u');
        $query
            ->where('u.lastLogin LIKE :today')
            ->setParameter('today', $date->format('Y-m-d') . '%');

        return $query->getQuery()->getResult();
    }
}
