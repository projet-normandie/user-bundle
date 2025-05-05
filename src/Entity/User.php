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
use ApiPlatform\Metadata\Put;
use ApiPlatform\OpenApi\Model;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use ProjetNormandie\UserBundle\Controller\User\Autocomplete;
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
        new GetCollection(
            uriTemplate: '/users/autocomplete',
            controller: Autocomplete::class,
            openapi: new Model\Operation(
                responses: [
                    '200' => new Model\Response(description: 'Users retrieved successfully')
                ],
                summary: 'Retrieves users by autocompletion',
                description: 'Retrieves users by autocompletion',
                parameters: [
                    new Model\Parameter(
                        name: 'query',
                        in: 'query',
                        required: true,
                        schema: [
                            'type' => 'string'
                        ]
                    )
                ],
                security: [],
            ),
            normalizationContext: ['groups' => [
                'user:read']
            ],
        ),
        new Post(
            denormalizationContext: ['groups' => ['user:create']],
            validationContext: ['groups' => ['Default', 'user:create']],
        ),
        new Put(
            denormalizationContext: ['groups' => ['user:update']],
            validationContext: ['groups' => ['Default', 'user:update']],
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
    #[ORM\Column(type: 'array', nullable: false)]
    private array $roles = [];

    #[ORM\Column(length: 255)]
    private ?string $password;

    /**
     * Plain password. Used for model validation. Must not be persisted.
     */
    #[Assert\NotBlank(groups: ['user:create'])]
    #[Groups(['user:create'])]
    protected ?string $plainPassword = null;

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

    /**
     * Extra data for extensions and additional bundles.
     * @var array<string, mixed>
     */
    #[Groups(['user:read'])]
    #[ORM\Column(type: 'json', nullable: true)]
    protected array $extraData = [];

    #[ORM\Column(length: 255, nullable: false, options: ['default' => 'default.png'])]
    protected string $avatar = 'default.png';

    #[ORM\Column(length: 1000, nullable: true)]
    protected ?string $comment = null;

    #[Groups(['user:read', 'user:update'])]
    #[ORM\Column(length: 2, nullable: false, options: ['default' => 'en'])]
    protected string $locale = 'en';

    #[ORM\Column(length: 128)]
    #[Gedmo\Slug(fields: ['username'])]
    protected string $slug;

    #[ORM\JoinTable(name: 'pnu_user_group')]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'group_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: Group::class)]
    protected Collection $groups;

    public function __construct()
    {
        $this->groups = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername($username): void
    {
        $this->username = $username;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function setEnabled($boolean): void
    {
        $this->enabled = (bool) $boolean;
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

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

    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

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

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function hasRole($role): bool
    {
        return in_array(strtoupper($role), $this->getRoles(), true);
    }

    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getLastLogin(): ?DateTime
    {
        return $this->lastLogin;
    }

    public function setLastLogin(?DateTime $time = null): void
    {
        $lastLogin = $this->getLastLogin();
        if (($lastLogin === null) || ($lastLogin->format('Y-m-d') != $time->format('Y-m-d'))) {
            ++$this->nbConnexion;
        }
        $this->lastLogin = $time;
    }

    public function setConfirmationToken($confirmationToken): void
    {
        $this->confirmationToken = $confirmationToken;
    }

    public function getConfirmationToken(): ?string
    {
        return $this->confirmationToken;
    }

    public function setPasswordRequestedAt(?DateTime $date = null): void
    {
        $this->passwordRequestedAt = $date;
    }

    public function getPasswordRequestedAt(): ?DateTime
    {
        return $this->passwordRequestedAt;
    }

    public function isPasswordRequestExpired($ttl): bool
    {
        return $this->getPasswordRequestedAt() instanceof DateTime &&
               $this->getPasswordRequestedAt()->getTimestamp() + $ttl < time();
    }

    public function getLocale(): string
    {
        return $this->locale;
    }

    public function setLocale(string $locale): void
    {
        $this->locale = $locale;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function getNbConnexion(): int
    {
        return $this->nbConnexion;
    }

    public function setNbConnexion(int $nbConnexion): void
    {
        $this->nbConnexion = $nbConnexion;
    }

    /**
     * Get extra data by key or all extra data if no key provided.
     *
     * @param string|null $key The key to retrieve
     * @return mixed The value or all extraData if no key provided
     */
    public function getExtraData(?string $key = null): mixed
    {
        if ($key === null) {
            return $this->extraData;
        }

        return $this->extraData[$key] ?? null;
    }

    /**
     * Set extra data with a given key.
     *
     * @param string $key The key to set
     * @param mixed $value The value to set
     */
    public function setExtraData(string $key, mixed $value): void
    {
        $this->extraData[$key] = $value;
    }

    /**
     * Check if extra data with given key exists.
     *
     * @param string $key The key to check
     * @return bool True if the key exists
     */
    public function hasExtraData(string $key): bool
    {
        return array_key_exists($key, $this->extraData);
    }

    /**
     * Remove extra data with given key.
     *
     * @param string $key The key to remove
     */
    public function removeExtraData(string $key): void
    {
        if ($this->hasExtraData($key)) {
            unset($this->extraData[$key]);
        }
    }

    public function getAvatar(): string
    {
        return $this->avatar;
    }

    public function setAvatar(string $avatar): void
    {
        $this->avatar = $avatar;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment = null): void
    {
        $this->comment = $comment;
    }

    public function setGroups($groups): void
    {
        $this->groups = $groups;
    }

    public function getGroups(): Collection
    {
        return $this->groups;
    }

    public function setPlainPassword($password): void
    {
        $this->plainPassword = $password;
    }

    public function addGroup($group): void
    {
        $this->groups[] = $group;
    }

    public function removeGroup(Group $group): void
    {
        $this->groups->removeElement($group);
    }

    public function __toString()
    {
        return sprintf('%s [%d]', $this->getUsername(), $this->getId());
    }

    public function addRole($role): void
    {
        $role = strtoupper($role);
        if (!in_array($role, $this->roles, true)) {
            $this->roles[] = $role;
        }
    }

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
