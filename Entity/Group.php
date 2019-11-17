<?php

namespace ProjetNormandie\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\Group as BaseGroup;


/**
 * @ORM\Entity
 * @ORM\Table(name="groupRole")
 */
class Group extends BaseGroup
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var int
     */
    protected $id;

    /**
     * @inheritDoc
     */
    public function __toString()
    {
        return sprintf('%s [%d]', $this->getName(), $this->getId());
    }

    public function __construct()
    {
        parent::__construct('DEFAULT');
    }
}
