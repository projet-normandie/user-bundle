<?php
namespace ProjetNormandie\UserBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use ProjetNormandie\UserBundle\Entity\Ip;
use ProjetNormandie\UserBundle\Entity\UserIp;
use Symfony\Component\HttpFoundation\Request;

class IpManager
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param $user
     */
    public function majUserIp($user)
    {
        $request = Request::createFromGlobals();
        if ($user !== null) {
            $clientIp = $request->getClientIp();
            $ip = $this->em->getRepository('ProjetNormandieUserBundle:Ip')
                ->findOneBy(array('label' => $clientIp));
            if ($ip === null) {
                $ip = new Ip();
                $ip->setlabel($clientIp);
                $this->em->persist($ip);
                $this->em->flush();
            }

            $userIp = $this->em->getRepository('ProjetNormandie\UserBundle\Entity\UserIp')
                ->findOneBy(array('ip' => $ip, 'user' => $user));
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
