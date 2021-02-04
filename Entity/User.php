<?php

namespace ProjetNormandie\UserBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use Knp\DoctrineBehaviors\Contract\Entity\TimestampableInterface;
use Knp\DoctrineBehaviors\Model\Timestampable\TimestampableTrait;
use Knp\DoctrineBehaviors\Contract\Entity\SluggableInterface;
use Knp\DoctrineBehaviors\Model\Sluggable\SluggableTrait;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Serializer\Filter\GroupFilter;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="ProjetNormandie\UserBundle\Repository\UserRepository")
 * @DoctrineAssert\UniqueEntity(fields={"email"})
 * @DoctrineAssert\UniqueEntity(fields={"username"})
 * @ApiFilter(
 *     SearchFilter::class,
 *     properties={
 *          "lastLogin" : "partial"
 *      }
 * )
 * @ApiFilter(
 *     GroupFilter::class,
 *     arguments={
 *          "parameterName": "groups",
 *          "overrideDefaultGroups": true,
 *          "whitelist": {"user.read.mini"}
 *     }
 * )
 */
class User extends BaseUser implements UserPersonalDataInterface, UserCommunicationDataInterface, TimestampableInterface, SluggableInterface
{
    use UserPersonalDataTrait;
    use UserCommunicationDataTrait;
    use TimestampableTrait;
    use SluggableTrait;

    public const GENDER_FEMALE = 'F';
    public const GENDER_MALE = 'M';
    public const GENDER_UNDEFINED = 'I';

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
     * @var integer
     *
     * @ORM\Column(name="nbForumMessage", type="integer", nullable=false)
     */
    protected $nbForumMessage = 0;

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
     * @ORM\OneToMany(targetEntity="ProjetNormandie\UserBundle\Entity\UserIp", mappedBy="user")
     */
    private $userIp;


    /**
     * @var string
     * @ORM\Column(name="avatar", type="string", length=100, nullable=false)
     */
    protected $avatar = 'default.png';

    /**
     * @var string
     * @ORM\Column(name="comment", type="text", length=100, nullable=false)
     */
    protected $comment;


    /**
     * @var string
     * @ORM\Column(name="locale", type="string", length=2, nullable=true)
     */
    protected $locale = 'en';

    /**
     * @var Status
     *
     * @ORM\ManyToOne(targetEntity="ProjetNormandie\UserBundle\Entity\Status")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idStatus", referencedColumnName="id", nullable=false)
     * })
     */
    private $status;

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
    public function setLocale(string $locale)
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
    public function setNbConnexion(int $nbConnexion)
    {
        $this->nbConnexion = $nbConnexion;
        return $this;
    }

    /**
     * @return int
     */
    public function getNbForumMessage()
    {
        return $this->nbForumMessage;
    }

    /**
     * @param int $nbForumMessage
     * @return User
     */
    public function setNbForumMessage(int $nbForumMessage)
    {
        $this->nbForumMessage = $nbForumMessage;
        return $this;
    }

    /**
     * @return string
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * @param string $avatar
     * @return User
     */
    public function setAvatar(string $avatar)
    {
        $this->avatar = $avatar;
        return $this;
    }

    /**
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param string $comment
     * @return User
     */
    public function setComment(string $comment)
    {
        $this->comment = $comment;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setLastLogin(DateTime $time = null)
    {
        $lastLogin = $this->getLastLogin();
        if (($lastLogin === null) || ($lastLogin->format('Y-m-d') != $time->format('Y-m-d'))) {
            ++$this->nbConnexion;
        }
        $this->lastLogin = $time;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUserIp()
    {
        return $this->userIp;
    }

    /**
     * Set status
     * @param Status|object|null $status
     * @return $this
     */
    public function setStatus(Status $status = null)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return Status
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @inheritDoc
     */
    public function __toString()
    {
        return sprintf('%s [%d]', $this->getUsername(), $this->getId());
    }

    /**
     * Returns an array of the fields used to generate the slug.
     *
     * @return string[]
     */
    public function getSluggableFields(): array
    {
        return ['username'];
    }
}
