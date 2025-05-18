<?php

namespace ProjetNormandie\UserBundle\EventSubscriber;

use Doctrine\ORM\EntityManagerInterface;
use Gesdinet\JWTRefreshTokenBundle\Model\RefreshTokenManagerInterface;
use ProjetNormandie\UserBundle\Entity\RefreshToken;
use ProjetNormandie\UserBundle\Security\Event\SecurityEventTypeEnum;
use ProjetNormandie\UserBundle\Security\SecurityHistoryManager;
use Psr\Log\LoggerInterface;
use ProjetNormandie\UserBundle\Event\PasswordChangedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class PasswordChangeSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly MailerInterface $mailer,
        private readonly EntityManagerInterface $entityManager,
        private readonly TranslatorInterface $translator,
        private readonly RequestStack $requestStack,
        private readonly SecurityHistoryManager $securityHistoryManager,
        private readonly RefreshTokenManagerInterface $refreshTokenManager,
    ) {
    }

    /**
     * @return array<string, array<int, array<int, int|string>>>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            PasswordChangedEvent::class => [
                ['logPasswordChange', 100],
                ['notifyUser', 90],
                ['enforceSecurityMeasures', 80],
            ],
        ];
    }

    public function logPasswordChange(PasswordChangedEvent $event): void
    {
        $user = $event->getUser();
        $request = $this->requestStack->getCurrentRequest();
        $ip = $request ? $request->getClientIp() : 'unknown';

        $this->logger->info(
            sprintf(
                '##PASSWORD_CHANGED##[IP=%s/user=%s/id=%d]',
                $ip,
                $user->getUsername(),
                $user->getId()
            ),
            [
                'user_id' => $user->getId(),
                'username' => $user->getUsername(),
                'ip' => $ip,
                'action' => 'password_change',
                'timestamp' => new \DateTime()
            ]
        );
    }

    public function notifyUser(PasswordChangedEvent $event): void
    {
        $user = $event->getUser();

        $this->logger->debug('Sending password change notification', [
            'user_id' => $user->getId(),
            'username' => $user->getUsername()
        ]);

        try {
            $locale = $user->getLanguage();

            $emailBody = $this->translator->trans(
                'password_change.notification',
                [
                    '%username%' => $user->getUsername(),
                    '%date%' => (new \DateTime())->format('d/m/Y H:i:s')
                ],
                'email',
                $locale
            );

            $email = (new Email())
                ->to($user->getEmail())
                ->subject($this->translator->trans('password_change.subject', [], 'email', $locale))
                ->text($emailBody)
                ->html($emailBody);

            $this->mailer->send($email);

            $this->logger->info('Password change notification sent successfully', [
                'user_id' => $user->getId(),
                'username' => $user->getUsername(),
                'email' => $user->getEmail()
            ]);
        } catch (\Exception $e) {
            $this->logger->error('Error sending password change notification', [
                'user_id' => $user->getId(),
                'username' => $user->getUsername(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }


    public function enforceSecurityMeasures(PasswordChangedEvent $event): void
    {
        $user = $event->getUser();

        $this->logger->debug('Applying security measures after password change', [
            'user_id' => $user->getId(),
            'username' => $user->getUsername()
        ]);

        // Revoke all refresh tokens for JWT authentication
        $username = $user->getUsername();

        $tokens = $this->entityManager->getRepository(RefreshToken::class)
            ->createQueryBuilder('t')
            ->where('t.username = :username')
            ->setParameter('username', $username)
            ->getQuery()
            ->getResult();

        foreach ($tokens as $token) {
            $this->refreshTokenManager->delete($token);
        }

        $this->logger->info('Revoked all JWT refresh tokens after password change', [
            'user_id' => $user->getId(),
            'token_count' => count($tokens)
        ]);


        // Record password change in security history
        $this->securityHistoryManager->recordEvent(
            $user,
            SecurityEventTypeEnum::PASSWORD_CHANGE,
            [
                'timestamp' => new \DateTime(),
            ]
        );

        $this->logger->info('Recorded password change in security history', [
            'user_id' => $user->getId()
        ]);
    }
}
