<?php

namespace ProjetNormandie\UserBundle\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Events as LexikEvents;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use ProjetNormandie\UserBundle\Entity\User;
use ProjetNormandie\UserBundle\Security\Event\SecurityEventTypeEnum;
use ProjetNormandie\UserBundle\Security\SecurityHistoryManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;

class LoginListener implements EventSubscriberInterface
{
    public function __construct(
        private readonly SecurityHistoryManager $securityHistoryManager,
    ) {
    }

    /**
     * @return string[]
     */
    public static function getSubscribedEvents(): array
    {
        return array(
            LexikEvents::AUTHENTICATION_SUCCESS => 'success'
        );
    }


    /**
     * @param AuthenticationSuccessEvent $event
     * @return void
     */
    public function success(AuthenticationSuccessEvent $event): void
    {
        $request = Request::createFromGlobals();
        /** @var User $user */
        $user = $event->getUser();
        $this->securityHistoryManager->recordEvent($user, SecurityEventTypeEnum::LOGIN_SUCCESS);
    }
}
