<?php

namespace ProjetNormandie\UserBundle\Repository;

use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
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
