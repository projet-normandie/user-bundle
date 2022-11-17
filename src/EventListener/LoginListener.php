<?php

namespace ProjetNormandie\UserBundle\EventListener;

use Exception;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\SecurityEvents;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Doctrine\ORM\EntityManagerInterface;

class LoginListener implements EventSubscriberInterface
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @return string[]
     */
    public static function getSubscribedEvents(): array
    {
        return array(
            SecurityEvents::INTERACTIVE_LOGIN => 'onLogin',
        );
    }

    /**
     * @param $event
     * @throws Exception
     */
    public function onLogin($event)
    {
        $user = null;
        if ($event instanceof InteractiveLoginEvent) {
            $user = $event->getAuthenticationToken()->getUser();
        }

        if ($user !== null) {
            $user->setLastLogin(new \Datetime());
            $this->em->flush();
        }
    }
}
