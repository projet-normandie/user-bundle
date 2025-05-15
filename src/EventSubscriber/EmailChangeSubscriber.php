<?php

namespace ProjetNormandie\UserBundle\EventSubscriber;

use Psr\Log\LoggerInterface;
use ProjetNormandie\UserBundle\Event\EmailChangedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Contracts\Translation\TranslatorInterface;

class EmailChangeSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly MailerInterface $mailer,
        private readonly TranslatorInterface $translator
    ) {
    }

    /**
     * @return array<string, array<int, array<int, int|string>>>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            EmailChangedEvent::class => [
                ['logEmailChange', 100],
                ['notifyUser', 90],
            ],
        ];
    }

    public function logEmailChange(EmailChangedEvent $event): void
    {
        $user = $event->getUser();
        $oldEmail = $event->getOldEmail();
        $newEmail = $event->getNewEmail();

        $this->logger->info(
            sprintf(
                '##EMAIL_CHANGED##[user=%s/id=%d/old=%s/new=%s]',
                $user->getUsername(),
                $user->getId(),
                $oldEmail,
                $newEmail
            ),
            [
                'user_id' => $user->getId(),
                'username' => $user->getUsername(),
                'old_email' => $oldEmail,
                'new_email' => $newEmail,
                'action' => 'email_change'
            ]
        );
    }

    public function notifyUser(EmailChangedEvent $event): void
    {
        $user = $event->getUser();
        $oldEmail = $event->getOldEmail();
        $newEmail = $event->getNewEmail();

        $this->logger->debug('Sending email change notifications', [
            'user_id' => $user->getId(),
            'username' => $user->getUsername()
        ]);

        try {
            $locale = $user->getLanguage();

            // Email to old email
            $oldEmailBody = $this->translator->trans(
                'email_change.notification_old_email',
                [
                    '%username%' => $user->getUsername(),
                    '%old_email%' => $oldEmail,
                    '%new_email%' => $newEmail
                ],
                'email',
                $locale
            );

            $emailToOld = (new Email())
                ->to($oldEmail)
                ->subject($this->translator->trans('email_change.subject', [], 'email', $locale))
                ->text($oldEmailBody)
                ->html($oldEmailBody);

            $this->mailer->send($emailToOld);

            // Email to new email
            $newEmailBody = $this->translator->trans(
                'email_change.notification_new_email',
                [
                    '%username%' => $user->getUsername(),
                    '%old_email%' => $oldEmail,
                    '%new_email%' => $newEmail
                ],
                'email',
                $locale
            );

            $emailToNew = (new Email())
                ->to($newEmail)
                ->subject($this->translator->trans('email_change.subject', [], 'email', $locale))
                ->text($newEmailBody)
                ->html($newEmailBody);

            $this->mailer->send($emailToNew);

            $this->logger->info('Email change notifications sent successfully', [
                'user_id' => $user->getId(),
                'username' => $user->getUsername()
            ]);
        } catch (\Exception $e) {
            $this->logger->error('Error sending email change notifications', [
                'user_id' => $user->getId(),
                'username' => $user->getUsername(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
}
