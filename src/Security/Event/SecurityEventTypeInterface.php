<?php

namespace ProjetNormandie\UserBundle\Security\Event;

/**
 * Interface defining security event types
 */
interface SecurityEventTypeInterface
{
    /**
     * Get the event type code (used in database)
     *
     * @return string The event type code
     */
    public function getCode(): string;

    /**
     * Get the event type label (for display)
     *
     * @return string The human-readable label
     */
    public function getLabel(): string;

    /**
     * Get the icon class (FontAwesome)
     *
     * @return string The icon class
     */
    public function getIcon(): string;

    /**
     * Get the CSS class (e.g. bootstrap bg-* classes)
     *
     * @return string The CSS class
     */
    public function getCssClass(): string;

    /**
     * Get the severity level (info, warning, error, critical)
     *
     * @return string The severity level
     */
    public function getSeverity(): string;

    /**
     * Check if this event type matches a given code
     *
     * @param string $code The code to check
     * @return bool True if this event type matches the code
     */
    public function is(string $code): bool;
}
