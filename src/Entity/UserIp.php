<?php

namespace ProjetNormandie\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\TimestampableInterface;
use Knp\DoctrineBehaviors\Model\Timestampable\TimestampableTrait;

/**
 * Ip
 *
 * @ORM\Table(name="user_ip")
 * @ORM\Entity(repositoryClass="ProjetNormandie\UserBundle\Repository\UserIpRepository")
 */
class UserIp implements TimestampableInterface
{
    use TimestampableTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected int $id;

    /**
     * @ORM\Column(name="nbConnexion", type="integer", nullable=false)
     */
    protected int $nbConnexion = 0;

    /**
     * @ORM\ManyToOne(targetEntity="ProjetNormandie\UserBundle\Entity\User", inversedBy="userIp")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idUser", referencedColumnName="id", nullable=false)
     * })
     */
    private User $user;

    /**
     * @ORM\ManyToOne(targetEntity="ProjetNormandie\UserBundle\Entity\Ip", inversedBy="userIp")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idIp", referencedColumnName="id", nullable=false)
     * })
     */
    private Ip $ip;

    /**
     * Set id
     * @param integer $id
     * @return $this
     */
    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Get id
     * @return integer
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Set user
     * @param User|null $user
     * @return $this
     */
    public function setUser(User $user = null): self
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Get user
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * Set ip
     * @param Ip|null $ip
     * @return $this
     */
    public function setIp(Ip $ip = null): self
    {
        $this->ip = $ip;

        return $this;
    }

    /**
     * Get ip
     * @return Ip
     */
    public function getIp(): Ip
    {
        return $this->ip;
    }

    /**
     * @return int
     */
    public function getNbConnexion(): int
    {
        return $this->nbConnexion;
    }

    /**
     * @param int $nbConnexion
     * @return $this
     */
    public function setNbConnexion(int $nbConnexion): self
    {
        $this->nbConnexion = $nbConnexion;
        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return sprintf(
            '%s # %s # [%d] # %s',
            $this->getUser()->getUserName(),
            $this->getIp()->getLabel(),
            $this->getNbConnexion(),
            $this->getUpdatedAt()->format('Y-m-d')
        );
    }
}
