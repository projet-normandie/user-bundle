<?php

declare(strict_types=1);

namespace ProjetNormandie\UserBundle\EventListener\Entity;

use Doctrine\ORM\Event\PostUpdateEventArgs;
use Exception;
use ProjetNormandie\UserBundle\Entity\User;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use ProjetNormandie\UserBundle\Event\EmailChangedEvent;
use ProjetNormandie\UserBundle\Event\PasswordChangedEvent;
use ProjetNormandie\UserBundle\Util\TokenGenerator;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserListener
{
    private array $emailChangeData = [];
    private array $passwordChangeData = [];

    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly TokenGenerator $tokenGenerator,
        private readonly EventDispatcherInterface $eventDispatcher
    ) {
    }

    /**
     * @param User $user
     * @throws Exception
     */
    public function prePersist(User $user): void
    {
        $plaintextPassword = $user->getPlainPassword();

        if ($plaintextPassword === null) {
            return;
        }

        // hash the password (based on the security.yaml config for the $user class)
        $hashedPassword = $this->passwordHasher->hashPassword(
            $user,
            $plaintextPassword
        );
        $user->setPassword($hashedPassword);

        $user->setConfirmationToken($this->tokenGenerator->generateToken());
    }

    /**
     * @param User $user
     * @param PreUpdateEventArgs $event
     */
    public function preUpdate(User $user, PreUpdateEventArgs $event): void
    {
        if ($event->hasChangedField('email')) {
            $oldEmail = $event->getOldValue('email');
            $newEmail = $event->getNewValue('email');

            $this->emailChangeData[$user->getId()] = [
                'oldEmail' => $oldEmail,
                'newEmail' => $newEmail
            ];
        }

        $plainPassword = $user->getPlainPassword();
        if ($plainPassword !== null) {
            $hashedPassword = $this->passwordHasher->hashPassword(
                $user,
                $plainPassword
            );
            $user->setPassword($hashedPassword);

            $this->passwordChangeData[$user->getId()] = true;
        }
    }

    /**
     * @param User $user
     * @param PostUpdateEventArgs $event
     */
    public function postUpdate(User $user, PostUpdateEventArgs $event): void
    {
        if (isset($this->emailChangeData[$user->getId()])) {
            $data = $this->emailChangeData[$user->getId()];

            $emailChangedEvent = new EmailChangedEvent($user, $data['oldEmail'], $data['newEmail']);
            $this->eventDispatcher->dispatch($emailChangedEvent);

            unset($this->emailChangeData[$user->getId()]);
        }

        if (isset($this->passwordChangeData[$user->getId()])) {
            $passwordChangedEvent = new PasswordChangedEvent($user);
            $this->eventDispatcher->dispatch($passwordChangedEvent);

            unset($this->passwordChangeData[$user->getId()]);
        }
    }
}
