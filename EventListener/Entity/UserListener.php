<?php

namespace ProjetNormandie\UserBundle\EventListener\Entity;

use Doctrine\ORM\ORMException;
use Doctrine\ORM\Event\LifecycleEventArgs;
use ProjetNormandie\UserBundle\Entity\User;

class UserListener
{
    /**
     * @param User        $user
     * @param LifecycleEventArgs $event
     * @throws ORMException
     */
    public function prePersist(User $user, LifecycleEventArgs $event)
    {
        $em = $event->getEntityManager();
        $user->setStatus($em->getReference('ProjetNormandie\UserBundle\Entity\Status', 1));
    }
}
