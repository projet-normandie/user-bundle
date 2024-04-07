<?php

namespace ProjetNormandie\UserBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Bundle\DoctrineBundle\Registry;

abstract class DefaultRepository extends ServiceEntityRepository
{
    protected string $entityClass;

    public function __construct(Registry $registry, $entityClass)
    {
        $this->entityClass = $entityClass;
        parent::__construct($registry, $entityClass);
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
