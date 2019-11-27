<?php

namespace ProjetNormandie\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use Knp\DoctrineBehaviors\Model\Timestampable\Timestampable;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;
use videoGamesRecords\CoreBundle\Entity\Player;

/**
 * User
 *
 * @ORM\Entity
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="ProjetNormandie\UserBundle\Repository\UserRepository")
 * @DoctrineAssert\UniqueEntity(fields={"email"})
 * @DoctrineAssert\UniqueEntity(fields={"username"})
 */
class User extends BaseUser implements UserPersonalDataInterface, UserCommunicationDataInterface
{
    use UserPersonalDataTrait;
    use UserCommunicationDataTrait;
    use Timestampable;

    const GENDER_FEMALE = 'F';
    const GENDER_MALE = 'M';
    const GENDER_UNDEFINED = 'I';

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
     * @ORM\ManyToMany(targetEntity="ProjetNormandie\UserBundle\Entity\Group")
     * @ORM\JoinTable(name="user_group",
     *      joinColumns={@ORM\JoinColumn(name="userId", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="groupId", referencedColumnName="id")}
     * )
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $groups;

    /**
     * @var string
     * @ORM\Column(name="locale", type="string", length=2, nullable=true)
     */
    protected $locale = 'en';

    /**
     * {@inheritdoc}
     */
    public function __construct()
    {
        parent::__construct();
        $this->gender = self::GENDER_UNDEFINED;
    }


    /**
     * @{@inheritdoc}
     */
    public function setEmail($email)
    {
        parent::setEmail($email);
        if (empty($this->username)) {
            $this->setUsername($email);
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * @param string $locale
     * @return User
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;
        return $this;
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
     * @return User
     */
    public function setNbConnexion($nbConnexion)
    {
        $this->nbConnexion = $nbConnexion;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setLastLogin(\DateTime $time = null)
    {
        $this->lastLogin = $time;
        ++$this->nbConnexion;

        return $this;
    }


    /**
     * @inheritDoc
     */
    public function __toString()
    {
        return sprintf('%s [%d]', $this->getUsername(), $this->getId());
    }
}
