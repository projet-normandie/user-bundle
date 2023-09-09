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
     */
    protected ?int $id = null;

    /**
     * @ORM\Column(name="status", type="string", length=30, nullable=false)
     */
    protected string $status = self::STATUS_NORMAL;

    /**
     * @ORM\Column(name="label", type="string", length=30, nullable=false)
     */
    protected string $label = '';

    /**
     * @ORM\OneToMany(targetEntity="ProjetNormandie\UserBundle\Entity\UserIp", mappedBy="ip")
     */
    private $userIp;

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
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     * @return $this
     */
    public function setStatus(string $status): self
    {
        $this->status = $status;
        return $this;
    }

    /**
     * Set ip
     * @param string $label
     * @return $this
     */
    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Get label
     *
     * @return string
     */
    public function getLabel(): string
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
     * @return bool
     */
    public function isBanned(): bool
    {
        if ($this->getStatus() == self::STATUS_BANNED) {
            return true;
        }
        return false;
    }
    /**
     * @return array
     */
    public static function getStatusChoices(): array
    {
        return [
            self::STATUS_NORMAL => self::STATUS_NORMAL,
            self::STATUS_SUSPICIOUS => self::STATUS_SUSPICIOUS,
            self::STATUS_BANNED => self::STATUS_BANNED,
        ];
    }
}
