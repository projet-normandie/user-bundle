<?php

namespace ProjetNormandie\UserBundle\Event;

use ProjetNormandie\UserBundle\Entity\User;
use Symfony\Contracts\EventDispatcher\Event;

class EmailChangedEvent extends Event
{
    public function __construct(
        private readonly User $user,
        private readonly string $oldEmail,
        private readonly string $newEmail
    ) {
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getOldEmail(): string
    {
        return $this->oldEmail;
    }

    public function getNewEmail(): string
    {
        return $this->newEmail;
    }
}
