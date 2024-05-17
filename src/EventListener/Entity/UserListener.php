<?php

declare(strict_types=1);

namespace ProjetNormandie\UserBundle\EventListener\Entity;

use ProjetNormandie\UserBundle\Entity\User;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class UserListener
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly ParameterBagInterface $params,
        private readonly TranslatorInterface $translator,
        private readonly MailerInterface $mailer
    ) {
    }

    /**
     * @param User $user
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
    }

    /**
     * @param User               $user
     * @param PreUpdateEventArgs $event
     */
    public function preUpdate(User $user, PreUpdateEventArgs $event): void
    {
        $plaintextPassword = $user->getPlainPassword();
        if ($plaintextPassword !== null) {
            $hashedPassword = $this->passwordHasher->hashPassword(
                $user,
                $plaintextPassword
            );
            $user->setPassword($hashedPassword);
        }
    }
    public function postPersist(User $user): void
    {
        $url = sprintf($this->params->get('pn.register.uri_confirmation'), $user->getConfirmationToken());
        $body = sprintf(
            $this->translator->trans('registration.email.message'),
            $user->getUsername(),
            $url
        );

        //dd($this->translator->trans('registration.email.message'));

        $email = (new Email())
            ->to($user->getEmail())
            ->subject(sprintf($this->translator->trans('registration.email.subject'), $user->getUsername()))
            ->text($body)
            ->html($body);

        $this->mailer->send($email);
    }
}
