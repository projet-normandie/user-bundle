<?php

namespace ProjetNormandie\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\TimestampableInterface;
use Knp\DoctrineBehaviors\Model\Timestampable\TimestampableTrait;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * Ip
 *
 * @ORM\Table(name="ip")
 * @ORM\Entity(repositoryClass="ProjetNormandie\UserBundle\Repository\IpRepository")
 * @DoctrineAssert\UniqueEntity(fields={"label"})
 */
class Ip implements TimestampableInterface
{
    use TimestampableTrait;

    public const STATUS_NORMAL = 'NORMAL';
    public const STATUS_SUSPICIOUS = 'SUSPICIOUS';
    public const STATUS_BANNED = 'BANNED';

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var int
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(name="status", type="string", length=30, nullable=false)
     */
    protected $status = self::STATUS_NORMAL;

    /**
     * @var string
     * @ORM\Column(name="label", type="string", length=30, nullable=false)
     */
    protected $label;

    /**
     * @ORM\OneToMany(targetEntity="ProjetNormandie\UserBundle\Entity\UserIp", mappedBy="ip")
     */
    private $userIp;

    /**
     * Set idGroup
     * @param integer $id
     * @return $this
     */
    public function setId(int $id)
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
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $status
     * @return $this
     */
    public function setStatus(string $status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * Set ip
     * @param null $label
     * @return $this
     */
    public function setlabel($label = null)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Get label
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @return mixed
     */
    public function getUserIp()
    {
        return $this->userIp;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return sprintf('%s', $this->getLabel());
    }

    /**
     * @return array
     */
    public static function getStatusChoices()
    {
        return [
            self::STATUS_NORMAL => self::STATUS_NORMAL,
            self::STATUS_SUSPICIOUS => self::STATUS_SUSPICIOUS,
            self::STATUS_BANNED => self::STATUS_BANNED,
        ];
    }
}
