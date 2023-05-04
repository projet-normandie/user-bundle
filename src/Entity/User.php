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
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
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
class User implements UserInterface, TimestampableInterface, SluggableInterface, PasswordAuthenticatedUserInterface
{
    use TimestampableTrait;
    use SluggableTrait;

    const ROLE_DEFAULT = 'ROLE_USER';
    const ROLE_ADMIN = 'ROLE_ADMIN';
    const ROLE_SUPER_ADMIN = 'ROLE_SUPER_ADMIN';

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected ?int $id = null;

    /**
     * @ORM\Column(type="string", length=50, unique=true)
     */
    protected string $username = '';

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private string $email = '';

    /**
     * @ORM\Column(type="boolean")
     */
    protected bool $enabled = false;

    /**
     * @ORM\Column(type="array")
     */
    private array $roles = [];

    /**
     * @ORM\Column(name="password", type="string")
     */
    private ?string $password;

    /**
     * Plain password. Used for model validation. Must not be persisted.
     */
    protected string $plainPassword = '';

    /**
     * @ORM\Column(name="last_login",type="datetime", nullable=true)
     */
    protected ?DateTime $lastLogin = null;

    /**
     * @ORM\Column(name="confirmation_token",type="string", length=180, nullable=true, unique=true)
     */
    protected ?string $confirmationToken = null;

    /**
     * @ORM\Column(name="password_requested_at",type="datetime", nullable=true)
     */
    protected ?DateTime $passwordRequestedAt = null;

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
    protected ?string $comment = null;

    /**
     * @ORM\Column(name="locale", type="string", length=2, nullable=true)
     */
    protected string $locale = 'en';

    /**
     * @ORM\Column(name="rules_accepted", type="boolean", nullable=false)
     */
    protected bool $rules_accepted = true;

    /**
     * @ORM\ManyToMany(targetEntity="ProjetNormandie\UserBundle\Entity\Group")
     * @ORM\JoinTable(name="user_group",
     *      joinColumns={@ORM\JoinColumn(name="userId", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="groupId", referencedColumnName="id")}
     * )
     * @var Collection
     */
    protected $groups;

    /**
     * @ORM\OneToMany(targetEntity="ProjetNormandie\UserBundle\Entity\UserIp", mappedBy="user")
     */
    private $userIp;


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
     * @return User
     */
    public function setUsername($username): User
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
     * @return User
     */
    public function setEmail(string $email): User
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @param $boolean
     * @return User
     */
    public function setEnabled($boolean): User
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
     * @return array|string[]
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
     * @return User
     */
    public function setRoles(array $roles): User
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @return string
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return User
     */
    public function setPassword(string $password): User
    {
        $this->password = $password;

        return $this;
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
     * @return string
     */
    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }


    /**
     * @param $role
     * @return bool
     */
    public function hasRole($role): bool
    {
        return in_array(strtoupper($role), $this->getRoles(), true);
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
     * @return User
     */
    public function setLastLogin(DateTime $time = null) : User
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
     * @return User
     */
    public function setConfirmationToken($confirmationToken): User
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
     * @return User
     */
    public function setPasswordRequestedAt(DateTime $date = null): User
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
    public function setLocale(string $locale): User
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
    public function setNbConnexion(int $nbConnexion): User
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
    public function setNbForumMessage(int $nbForumMessage): User
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
    public function setAvatar(string $avatar): User
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
     * @return User
     */
    public function setComment(string $comment = null) : User
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
     * Set rules_accepted
     * @param bool $rules_accepted
     * @return User
     */
    public function setRulesAccepted(bool $rules_accepted): User
    {
        $this->rules_accepted = $rules_accepted;
        return $this;
    }

    /**
     * Get rules_accepted
     * @return bool
     */
    public function getRulesAccepted(): bool
    {
        return $this->rules_accepted;
    }

    /**
     * @param $groups
     * @return User
     */
    public function setGroups($groups): User
    {
        $this->groups = $groups;
        return $this;
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
     * @return User
     */
    public function setPlainPassword($password): User
    {
        $this->plainPassword = $password;
        return $this;
    }

    /**
     * @param $group
     * @return User
     */
    public function addGroup($group): User
    {
        $this->groups[] = $group;
        return $this;
    }

     /**
     * @param Group $group
     */
    public function removeGroup(Group $group)
    {
        $this->groups->removeElement($group);
    }


    /**
     * @return string
     */
    public function __toString()
    {
        return sprintf('%s [%d]', $this->getUsername(), $this->getId());
    }

    /**
     * @param $role
     * @return User
     */
    public function addRole($role): User
    {
        $role = strtoupper($role);
        if ($role === static::ROLE_DEFAULT) {
            return $this;
        }
        if (!in_array($role, $this->roles, true)) {
            $this->roles[] = $role;
        }
        return $this;
    }

    /**
     * @param $role
     * @return User
     */
    public function removeRole($role): User
    {
        if (false !== $key = array_search(strtoupper($role), $this->roles, true)) {
            unset($this->roles[$key]);
            $this->roles = array_values($this->roles);
        }
        return $this;
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
