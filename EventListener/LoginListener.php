<?php

namespace ProjetNormandie\UserBundle\EventListener;

use FOS\UserBundle\FOSUserEvents;
use ProjetNormandie\UserBundle\Entity\Ip;
use ProjetNormandie\UserBundle\Entity\UserIp;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\SecurityEvents;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Doctrine\ORM\EntityManagerInterface;

class LoginListener implements EventSubscriberInterface
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            FOSUserEvents::SECURITY_IMPLICIT_LOGIN => 'onLogin',
            SecurityEvents::INTERACTIVE_LOGIN => 'onLogin',
        );
    }

    public function onLogin($event)
    {
        $user = null;
        if ($event instanceof UserEvent) {
            $user = $event->getUser();
        } elseif ($event instanceof InteractiveLoginEvent) {
            $user = $event->getAuthenticationToken()->getUser();
        }
        if ($user !== null) {
            $label = $_SERVER['REMOTE_ADDR'];
            $ip = $this->em->getRepository('ProjetNormandieUserBundle:Ip')->findOneBy(array('label' => $label));
            if ($ip === null) {
                $ip = new Ip();
                $ip->setlabel($label);
                $this->em->persist($ip);
                $this->em->flush();
            }

            $userIp = $this->em->getRepository('ProjetNormandieUserBundle:UserIp')->findOneBy(array('ip' => $ip, 'user' => $user));
            if ($userIp == null) {
                $userIp = new UserIp();
                $userIp->setUser($user);
                $userIp->setIp($ip);
                $this->em->persist($userIp);
            }
            $userIp->setNbConnexion($userIp->getNbConnexion() + 1);
            $this->em->flush();
        }
    }
}
