<?php

namespace ProjetNormandie\UserBundle\EventListener;

use Exception;
use Lexik\Bundle\JWTAuthenticationBundle\Events as LexikEvents;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use ProjetNormandie\UserBundle\Service\IpManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\SecurityEvents;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Doctrine\ORM\EntityManagerInterface;

class LoginListener implements EventSubscriberInterface
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em, IpManager $ipManager)
    {
        $this->em = $em;
        $this->ipManager = $ipManager;
    }

    /**
     * @return string[]
     */
    public static function getSubscribedEvents(): array
    {
        return array(
            SecurityEvents::INTERACTIVE_LOGIN => 'onLogin',
            LexikEvents::AUTHENTICATION_SUCCESS => 'majIp'
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

    /**
     * @param AuthenticationSuccessEvent $event
     * @return void
     */
    public function majIp(AuthenticationSuccessEvent $event)
    {
        $this->ipManager->majUserIp($event->getUser());
    }
}
