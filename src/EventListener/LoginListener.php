<?php

namespace ProjetNormandie\UserBundle\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Events as LexikEvents;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use ProjetNormandie\UserBundle\Security\UserProvider;
use ProjetNormandie\UserBundle\Service\IpManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class LoginListener implements EventSubscriberInterface
{
    private IpManager $ipManager;

    private UserProvider $userProvider;

    public function __construct(IpManager $ipManager, UserProvider $userProvider)
    {
        $this->ipManager = $ipManager;
        $this->userProvider = $userProvider;
    }

    /**
     * @return string[]
     */
    public static function getSubscribedEvents(): array
    {
        return array(
            LexikEvents::AUTHENTICATION_SUCCESS => 'majIp'
        );
    }


    /**
     * @param AuthenticationSuccessEvent $event
     * @return void
     */
    public function majIp(AuthenticationSuccessEvent $event)
    {
        $this->ipManager->majUserIp(
            $this->userProvider->loadUserByUsername($event->getUser()->getUsername()
            )
        );
    }
}
