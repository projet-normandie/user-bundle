<?php

declare(strict_types=1);

namespace ProjetNormandie\UserBundle\Tests\Builder;

use ProjetNormandie\UserBundle\Entity\User;
use ProjetNormandie\UserBundle\Tests\ProvideFaker;

final class UserBuilder implements BuilderInterface
{
    use ProvideFaker;

    private string $email = '';
    private string $username = '';
    /**
     * @var array<string>
     */
    private array $roles = [];

    public function __construct()
    {
        $this->username = $this->faker()->userName();
        $this->email = $this->faker()->email();
    }

    public function withoutProfile(): self
    {
        $this->roles = [];
        return $this;
    }

    /**
     * @param array<string> $roles
     * @return $this
     */
    public function withRoles(array $roles): self
    {
        $this->roles = $roles;
        return $this;
    }

    public function build(): object
    {
        $user = new User();
        $user->setUsername($this->username);
        $user->setEmail($this->email);
        $user->setRoles($this->roles);
        return $user;
    }
}
