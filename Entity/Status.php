<?php

namespace ProjetNormandie\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Knp\DoctrineBehaviors\Contract\Entity\TranslatableInterface;
use Knp\DoctrineBehaviors\Model\Translatable\TranslatableTrait;

/**
 * UserStatus
 *
 * @ORM\Table(name="user_status")
 * @ORM\Entity(repositoryClass="ProjetNormandie\UserBundle\Repository\StatusRepository")
 */
class Status implements TranslatableInterface
{
    use TranslatableTrait;


    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     */
    private $id;

    /**
     * @var string
     *
     * @Assert\Length(max="30")
     * @ORM\Column(name="class", type="string", length=30, nullable=false)
     */
    private $class;


    /**
     * @return string
     */
    public function __toString()
    {
        return sprintf('%s [%s]', $this->getDefaultName(), $this->id);
    }

    /**
     * @return string
     */
    public function getDefaultName()
    {
        return $this->translate('en', false)->getName();
    }

    /**
     * Set id
     * @param integer $id
     * @return $this
     */
    public function setId(int $id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * Set class
     * @param string $class
     * @return $this
     */
    public function setClass(string $class)
    {
        $this->class = $class;

        return $this;
    }

    /**
     * Get class
     *
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }


    /**
     * @param string $name
     * @return $this
     */
    public function setName(string $name)
    {
        $this->translate(null, false)->setName($name);

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->translate(null, false)->getName();
    }
}