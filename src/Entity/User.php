<?php

declare(strict_types=1);

namespace ProjetNormandie\UserBundle\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use ProjetNormandie\UserBundle\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Table(name:'pnu_user')]
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\EntityListeners(["ProjetNormandie\UserBundle\EventListener\Entity\UserListener"])]
#[DoctrineAssert\UniqueEntity(["email"])]
#[DoctrineAssert\UniqueEntity(["username"])]
#[ApiResource(
    operations: [
        new GetCollection(),
        new Post(
            denormalizationContext: ['groups' => ['user:create']],
            validationContext: ['groups' => ['Default', 'user:create']],
        ),
        new Get(),
    ],
    normalizationContext: ['groups' => ['user:read']]
)]
#[ApiFilter(SearchFilter::class, properties: ['id' => 'exact', 'username' => 'partial'])]
#[ApiFilter(OrderFilter::class, properties: ['id', 'username'], arguments: ['orderParameterName' => 'order'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    use TimestampableEntity;

    #[Groups(['user:read'])]
    #[ORM\Id, ORM\Column, ORM\GeneratedValue]
    protected ?int $id = null;


    #[Groups(['user:read', 'user:create'])]
    #[ORM\Column(length: 100, unique: true, nullable: false)]
    protected string $username = '';

    #[Groups(['user:read', 'user:create'])]
    #[ORM\Column(length: 180, unique: true, nullable: false)]
    private string $email = '';


    #[ORM\Column(nullable: false, options: ['default' => false])]
    protected bool $enabled = false;

    /**
     * @var array<string>
     */
    #[ORM\Column(type: 'json', nullable: false)]
    private array $roles = [];

    #[ORM\Column(length: 255)]
    private ?string $password;

    /**
     * Plain password. Used for model validation. Must not be persisted.
     */
    #[Assert\NotBlank(groups: ['user:create'])]
    #[Groups(['user:create', 'user:update'])]
    protected string $plainPassword = '';

    #[Groups(['user:read'])]
    #[ORM\Column(type: 'datetime', nullable: true)]
    protected ?DateTime $lastLogin = null;

    #[ORM\Column(length: 255, unique: true, nullable: true)]
    protected ?string $confirmationToken = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    protected ?DateTime $passwordRequestedAt = null;

    #[Groups(['user:read'])]
    #[ORM\Column(nullable: false, options: ['default' => 0])]
    protected int $nbConnexion = 0;

    #[ORM\Column(nullable: false)]
    protected int $nbForumMessage = 0;

    #[ORM\Column(length: 255, nullable: false, options: ['default' => 'default.png'])]
    protected string $avatar = 'default.png';

    #[ORM\Column(length: 1000, nullable: true)]
    protected ?string $comment = null;

    #[ORM\Column(length: 2, nullable: false, options: ['default' => 'en'])]
    protected string $locale = 'en';

    #[ORM\Column(length: 128, unique: true)]
    #[Gedmo\Slug(fields: ['username'])]
    protected string $slug;


    #[ORM\JoinTable(name: 'pnu_user_group')]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'group_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: Group::class)]
    protected Collection $groups;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->groups = new ArrayCollection();
    }


    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
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
     */
    public function setUsername($username): void
    {
        $this->username = $username;
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
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @param $boolean
     */
    public function setEnabled($boolean): void
    {
        $this->enabled = (bool) $boolean;
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
     */
    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }

    /**
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
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
     * @return string|null
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
    public function setLastLogin(DateTime $time = null): User
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
     */
    public function setConfirmationToken($confirmationToken): void
    {
        $this->confirmationToken = $confirmationToken;
    }

    /**
     * @return string|null
     */
    public function getConfirmationToken(): ?string
    {
        return $this->confirmationToken;
    }

    /**
     * @param DateTime|null $date
     */
    public function setPasswordRequestedAt(DateTime $date = null): void
    {
        $this->passwordRequestedAt = $date;
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
    public function isPasswordRequestExpired($ttl): bool
    {
        return $this->getPasswordRequestedAt() instanceof DateTime &&
               $this->getPasswordRequestedAt()->getTimestamp() + $ttl < time();
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
     */
    public function setLocale(string $locale): void
    {
        $this->locale = $locale;
    }

    /**
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     */
    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
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
     */
    public function setNbConnexion(int $nbConnexion): void
    {
        $this->nbConnexion = $nbConnexion;
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
     */
    public function setNbForumMessage(int $nbForumMessage): void
    {
        $this->nbForumMessage = $nbForumMessage;
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
     */
    public function setAvatar(string $avatar): void
    {
        $this->avatar = $avatar;
    }

    /**
     * @return string|null
     */
    public function getComment(): ?string
    {
        return $this->comment;
    }

    /**
     * @param string|null $comment
     */
    public function setComment(string $comment = null): void
    {
        $this->comment = $comment;
    }

    /**
     * @param $groups
     */
    public function setGroups($groups): void
    {
        $this->groups = $groups;
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
     */
    public function setPlainPassword($password): void
    {
        $this->plainPassword = $password;
    }

    /**
     * @param $group
     */
    public function addGroup($group): void
    {
        $this->groups[] = $group;
    }

     /**
     * @param Group $group
     */
    public function removeGroup(Group $group): void
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
     * @return void
     */
    public function addRole($role): void
    {
        $role = strtoupper($role);
        if (!in_array($role, $this->roles, true)) {
            $this->roles[] = $role;
        }
    }

    /**
     * @param $role
     * @return void
     */
    public function removeRole($role): void
    {
        if (false !== $key = array_search(strtoupper($role), $this->roles, true)) {
            unset($this->roles[$key]);
            $this->roles = array_values($this->roles);
        }
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }
}
