<?php

namespace ProjetNormandie\UserBundle\Entity;

use DateTime;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\TimestampableInterface;
use Knp\DoctrineBehaviors\Model\Timestampable\TimestampableTrait;
use Knp\DoctrineBehaviors\Contract\Entity\SluggableInterface;
use Knp\DoctrineBehaviors\Model\Sluggable\SluggableTrait;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;
use Symfony\Component\Security\Core\Role\Role;
use Symfony\Component\Security\Core\User\UserInterface;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Serializer\Filter\GroupFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="ProjetNormandie\UserBundle\Repository\UserRepository")
 * @ORM\EntityListeners({"ProjetNormandie\UserBundle\EventListener\Entity\UserListener"})
 * @DoctrineAssert\UniqueEntity(fields={"email"})
 * @DoctrineAssert\UniqueEntity(fields={"username"})
 * @ApiResource(attributes={"order"={"username": "ASC"}})
 * @ApiFilter(DateFilter::class, properties={"lastLogin": DateFilter::EXCLUDE_NULL})
 * @ApiFilter(
 *     GroupFilter::class,
 *     arguments={
 *          "parameterName": "groups",
 *          "overrideDefaultGroups": true,
 *          "whitelist": {"user.read.mini"}
 *     }
 * )
 */
class User implements UserInterface, TimestampableInterface, SluggableInterface
{
    use TimestampableTrait;
    use SluggableTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected int $id;

    /**
     * @ORM\Column(type="string", length=50, unique=true)
     */
    protected string $username;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private string $email;

    /**
     * @ORM\Column(type="boolean")
     */
    protected bool $enabled;

    /**
     * @ORM\Column(type="array")
     */
    private array $roles = [];

    /**
     * @ORM\Column(name="password", type="string")
     */
    private string $password;

    /**
     * Plain password. Used for model validation. Must not be persisted.
     */
    protected string $plainPassword = '';

    /**
     * @ORM\Column(name="salt", type="string")
     */
    private ?string $salt;

    /**
     * @ORM\Column(name="last_login",type="datetime", nullable=true)
     */
    protected ?DateTime $lastLogin;

    /**
     * @ORM\Column(name="confirmation_token",type="string", length=180, nullable=true, unique=true)
     */
    protected ?string $confirmationToken;

    /**
     * @ORM\Column(name="password_requested_at",type="datetime", nullable=true)
     */
    protected ?DateTime $passwordRequestedAt;

    /**
     * @ORM\Column(name="nbConnexion", type="integer", nullable=false)
     */
    protected int $nbConnexion = 0;

    /**
     * @ORM\Column(name="nbForumMessage", type="integer", nullable=false)
     */
    protected int $nbForumMessage = 0;

    /**
     * @ORM\Column(name="avatar", type="string", length=100, nullable=false)
     */
    protected string $avatar = 'default.png';

    /**
     * @ORM\Column(name="comment", type="text", length=100, nullable=true)
     */
    protected ?string $comment;

    /**
     * @ORM\Column(name="locale", type="string", length=2, nullable=true)
     */
    protected string $locale = 'en';

    /**
     * @ORM\ManyToMany(targetEntity="ProjetNormandie\UserBundle\Entity\Group")
     * @ORM\JoinTable(name="user_group",
     *      joinColumns={@ORM\JoinColumn(name="userId", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="groupId", referencedColumnName="id")}
     * )
     * @var Collection
     */
    protected Collection $groups;

    /**
     * @ORM\OneToMany(targetEntity="ProjetNormandie\UserBundle\Entity\UserIp", mappedBy="user")
     */
    private $userIp;

    /**
     * @ORM\ManyToOne(targetEntity="ProjetNormandie\UserBundle\Entity\Status")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idStatus", referencedColumnName="id", nullable=false)
     * })
     */
    private ?Status $status;

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
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param $username
     * @return $this
     */
    public function setUsername($username): self
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return $this
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @param $boolean
     * @return $this
     */
    public function setEnabled($boolean): self
    {
        $this->enabled = (bool) $boolean;

        return $this;
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * @return array|string[]|Role[]
     */
    public function getRoles(): array
    {
        $roles = $this->roles;

        foreach ($this->getGroups() as $group) {
            $roles = array_merge($roles, $group->getRoles());
        }

        // we need to make sure to have at least one role
        $roles[] = 'ROLE_USER';

        return array_values(array_unique($roles));
    }

    /**
     * @param array $roles
     * @return $this
     */
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return $this
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return string
     */
    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     *
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return DateTime|null
     */
    public function getLastLogin(): ?DateTime
    {
        return $this->lastLogin;
    }

    /**
     * @param DateTime|null $time
     * @return $this
     */
    public function setLastLogin(DateTime $time = null) : self
    {
        $lastLogin = $this->getLastLogin();
        if (($lastLogin === null) || ($lastLogin->format('Y-m-d') != $time->format('Y-m-d'))) {
            ++$this->nbConnexion;
        }
        $this->lastLogin = $time;
        return $this;
    }

    /**
     * @param $confirmationToken
     * @return $this
     */
    public function setConfirmationToken($confirmationToken): self
    {
        $this->confirmationToken = $confirmationToken;
        return $this;
    }

    /**
     * @return string
     */
    public function getConfirmationToken(): ?string
    {
        return $this->confirmationToken;
    }

    /**
     * @param DateTime|null $date
     * @return $this
     */
    public function setPasswordRequestedAt(DateTime $date = null): self
    {
        $this->passwordRequestedAt = $date;
        return $this;
    }

    /**
     * Gets the timestamp that the user requested a password reset.
     * @return null|DateTime
     */
    public function getPasswordRequestedAt(): ?DateTime
    {
        return $this->passwordRequestedAt;
    }

    /**
     * @param $ttl
     * @return bool
     */
    public function isPasswordRequestNonExpired($ttl): bool
    {
        return $this->getPasswordRequestedAt() instanceof DateTime &&
               $this->getPasswordRequestedAt()->getTimestamp() + $ttl > time();
    }

    /**
     * @return string
     */
    public function getLocale(): string
    {
        return $this->locale;
    }

    /**
     * @param string $locale
     * @return User
     */
    public function setLocale(string $locale): self
    {
        $this->locale = $locale;
        return $this;
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
     * @return User
     */
    public function setNbConnexion(int $nbConnexion): self
    {
        $this->nbConnexion = $nbConnexion;
        return $this;
    }

    /**
     * @return int
     */
    public function getNbForumMessage(): int
    {
        return $this->nbForumMessage;
    }

    /**
     * @param int $nbForumMessage
     * @return User
     */
    public function setNbForumMessage(int $nbForumMessage): self
    {
        $this->nbForumMessage = $nbForumMessage;
        return $this;
    }

    /**
     * @return string
     */
    public function getAvatar(): string
    {
        return $this->avatar;
    }

    /**
     * @param string $avatar
     * @return User
     */
    public function setAvatar(string $avatar): self
    {
        $this->avatar = $avatar;
        return $this;
    }

    /**
     * @return string
     */
    public function getComment(): ?string
    {
        return $this->comment;
    }

    /**
     * @param string|null $comment
     * @return $this
     */
    public function setComment(string $comment = null) : self
    {
        $this->comment = $comment;
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
    public function setStatus(Status $status = null): self
    {
        $this->status = $status;
        return $this;
    }

    /**
     * Get status
     * @return Status
     */
    public function getStatus(): Status
    {
        return $this->status;
    }

    /**
     * @return bool
     */
    public function getIsOnline(): bool
    {
        if ($this->getLastLogin() != null) {
            $now = new DateTime();
            if (($now->format('U') - $this->getLastLogin()->format('U')) < 300) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * @return Collection
     */
    public function getGroups(): Collection
    {
        return $this->groups;
    }

    /**
     * @param $password
     * @return $this
     */
    public function setPlainPassword($password): self
    {
        $this->plainPassword = $password;
        return $this;
    }


    /**
     * @return string
     */
    public function __toString()
    {
        return sprintf('%s [%d]', $this->getUsername(), $this->getId());
    }

    /**
     * Returns an array of the fields used to generate the slug.
     * @return string[]
     */
    public function getSluggableFields(): array
    {
        return ['username'];
    }
}
