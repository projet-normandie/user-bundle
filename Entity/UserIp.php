<?php

namespace ProjetNormandie\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model\Timestampable\Timestampable;

/**
 * Ip
 *
 * @ORM\Entity
 * @ORM\Table(name="user_ip")
 * @ORM\Entity(repositoryClass="ProjetNormandie\UserBundle\Repository\UserIpRepository")
 */
class UserIp
{
    use Timestampable;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var int
     */
    protected $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="nbConnexion", type="integer", nullable=false)
     */
    protected $nbConnexion = 0;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="ProjetNormandie\UserBundle\Entity\User", inversedBy="userIp")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idUser", referencedColumnName="id", nullable=false)
     * })
     */
    private $user;

    /**
     * @var Ip
     *
     * @ORM\ManyToOne(targetEntity="ProjetNormandie\UserBundle\Entity\Ip")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idIp", referencedColumnName="id", nullable=false)
     * })
     */
    private $ip;

    /**
     * Set idGroup
     * @param integer $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Get idGroup
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set user
     *
     * @param User $user
     * @return $this
     */
    public function setUser(User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set ip
     *
     * @param Ip $ip
     * @return $this
     */
    public function setIp(Ip $ip = null)
    {
        $this->ip = $ip;

        return $this;
    }

    /**
     * Get ip
     *
     * @return Ip
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * @return int
     */
    public function getNbConnexion()
    {
        return $this->nbConnexion;
    }

    /**
     * @param int $nbConnexion
     * @return $this
     */
    public function setNbConnexion($nbConnexion)
    {
        $this->nbConnexion = $nbConnexion;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function __toString()
    {
        return sprintf('%s # %s # [%d] # %s', $this->getUser()->getUserName(), $this->getIp()->getLabel(), $this->getNbConnexion() , $this->getUpdatedAt()->format('Y-m-d'));
    }
}