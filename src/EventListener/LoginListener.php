<?php

namespace ProjetNormandie\UserBundle\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Events as LexikEvents;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use ProjetNormandie\UserBundle\Entity\User;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;

class LoginListener implements EventSubscriberInterface
{
    public function __construct(
        private readonly LoggerInterface $logger
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
        $this->logger->info(
            sprintf(
                '##LOGIN##[IP=%s/username=%s]',
                $request->getClientIp(),
                $user->getUsername()
            )
        );
    }
}
