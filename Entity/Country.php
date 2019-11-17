<?php

namespace ProjetNormandie\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model\Translatable\Translatable;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Country
 *
 * @ORM\Table(name="country")
 * @ORM\Entity
 */
class Country
{
    use Translatable;

    /**
     * @var string
     *
     * @Assert\Length(min="2", max="2")
     * @ORM\Column(name="code_iso2", type="string", length=2, nullable=false)
     */
    private $codeIso2;

    /**
     * @var string
     *
     * @Assert\Length(min="3", max="3")
     * @ORM\Column(name="code_iso3", type="string", length=3, nullable=false)
     */
    private $codeIso3;

    /**
     * @var string
     *
     * @ORM\Column(name="code_iso_numeric", type="integer", nullable=false)
     */
    private $codeIsoNumeric;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * Set codeIso
     *
     * @param string $codeIso2
     * @return Country
     */
    public function setCodeIso2($codeIso2)
    {
        $this->codeIso2 = $codeIso2;

        return $this;
    }

    /**
     * Get codeIso
     *
     * @return string
     */
    public function getCodeIso2()
    {
        return $this->codeIso2;
    }

    /**
     * @return string
     */
    public function getCodeIso3()
    {
        return $this->codeIso3;
    }

    /**
     * @param string $codeIso3
     * @return Country
     */
    public function setCodeIso3($codeIso3)
    {
        $this->codeIso3 = $codeIso3;
        return $this;
    }

    /**
     * @return string
     */
    public function getCodeIsoNumeric()
    {
        return $this->codeIsoNumeric;
    }

    /**
     * @param string $codeIsoNumeric
     * @return Country
     */
    public function setCodeIsoNumeric($codeIsoNumeric)
    {
        $this->codeIsoNumeric = $codeIsoNumeric;
        return $this;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName($name)
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
     * Set id
     *
     * @param int $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function __toString()
    {
        return sprintf('%s [%d]', $this->getName(), $this->getId());
    }
}
