<?php

namespace ProjetNormandie\UserBundle\Event;

use ProjetNormandie\UserBundle\Entity\User;
use Symfony\Contracts\EventDispatcher\Event;

class PasswordChangedEvent extends Event
{
    public function __construct(
        private readonly User $user
    ) {
    }

    public function getUser(): User
    {
        return $this->user;
    }
}
