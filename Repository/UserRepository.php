<?php

namespace ProjetNormandie\UserBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;


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
}
