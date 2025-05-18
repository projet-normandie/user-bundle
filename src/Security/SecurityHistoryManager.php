<?php

namespace ProjetNormandie\UserBundle\Security;

use Doctrine\ORM\EntityManagerInterface;
use ProjetNormandie\UserBundle\Entity\User;
use ProjetNormandie\UserBundle\Entity\SecurityEvent;
use ProjetNormandie\UserBundle\Security\Event\SecurityEventTypeEnum;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Manages security event history for auditing and tracking security-related actions
 */
class SecurityHistoryManager
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly RequestStack $requestStack,
    ) {
    }

    /**
     * Record a security event for a user
     *
     * @param User $user The user
     * @param SecurityEventTypeEnum $eventType Type of security event (e.g., password_change, login, login_failure)
     * @param array $data Additional data to store with the event
     * @return SecurityEvent The created event
     */
    public function recordEvent(User $user, SecurityEventTypeEnum $eventType, array $data = []): SecurityEvent
    {
        // Record password change in security history
        $request = $this->requestStack->getCurrentRequest();
        $ip = $request ? $request->getClientIp() : 'unknown';
        $userAgent = $request ? $request->headers->get('User-Agent') : 'unknown';

        // Create a new security event
        $event = new SecurityEvent();
        $event->setUser($user);
        $event->setEventType($eventType->getCode());
        $event->setEventData($data);
        $event->setCreatedAt(new \DateTime());
        $event->setIpAddress($ip);
        $event->setUserAgent($userAgent);

        // Save the event
        $this->entityManager->persist($event);
        $this->entityManager->flush();

        return $event;
    }

    /**
     * Get recent security events for a user
     *
     * @param User $user The user
     * @param int $limit Maximum number of events to return
     * @return array List of security events
     */
    public function getRecentEvents(User $user, int $limit = 10): array
    {
        return $this->entityManager->getRepository(SecurityEvent::class)
            ->createQueryBuilder('e')
            ->where('e.user = :user')
            ->setParameter('user', $user)
            ->orderBy('e.createdAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * Get events of a specific type for a user
     *
     * @param User $user The user
     * @param string $eventType Type of security event
     * @param int $limit Maximum number of events to return
     * @return array List of security events
     */
    public function getEventsByType(User $user, string $eventType, int $limit = 10): array
    {
        return $this->entityManager->getRepository(SecurityEvent::class)
            ->createQueryBuilder('e')
            ->where('e.user = :user')
            ->andWhere('e.eventType = :eventType')
            ->setParameter('user', $user)
            ->setParameter('eventType', $eventType)
            ->orderBy('e.createdAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * Check if a specific event has occurred for a user within a time period
     *
     * @param User $user The user
     * @param string $eventType Type of security event
     * @param \DateInterval $interval Time interval to check
     * @return bool Whether the event occurred
     */
    public function hasEventOccurredWithin(User $user, string $eventType, \DateInterval $interval): bool
    {
        $date = new \DateTime();
        $date->sub($interval);

        $count = $this->entityManager->getRepository(SecurityEvent::class)
            ->createQueryBuilder('e')
            ->select('COUNT(e.id)')
            ->where('e.user = :user')
            ->andWhere('e.eventType = :eventType')
            ->andWhere('e.createdAt >= :date')
            ->setParameter('user', $user)
            ->setParameter('eventType', $eventType)
            ->setParameter('date', $date)
            ->getQuery()
            ->getSingleScalarResult();

        return $count > 0;
    }
}
