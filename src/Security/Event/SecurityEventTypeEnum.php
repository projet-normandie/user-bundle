<?php

namespace ProjetNormandie\UserBundle\Security\Event;

/**
 * Enum for security event types
 */
enum SecurityEventTypeEnum: string implements SecurityEventTypeInterface
{
    // Password event types
    case PASSWORD_CHANGE = 'password_change';
    case PASSWORD_RESET_REQUEST = 'password_reset_request';
    case PASSWORD_RESET_COMPLETE = 'password_reset_complete';

    // Email event types
    case EMAIL_CHANGE = 'email_change';
    case EMAIL_VERIFY = 'email_verify';

    // Authentication event types
    case LOGIN_SUCCESS = 'login_success';
    case LOGIN_FAILURE = 'login_failure';
    case LOGOUT = 'logout';
    case ACCOUNT_LOCKED = 'account_locked';

    // Account management event types
    case REGISTRATION = 'registration';
    case ACCOUNT_ACTIVATE = 'account_activate';
    case ACCOUNT_DEACTIVATE = 'account_deactivate';
    case PROFILE_UPDATE = 'profile_update';

    // Admin event types
    case ADMIN_LOGIN = 'admin_login';
    case ROLE_CHANGE = 'role_change';
    case USER_IMPERSONATE = 'user_impersonate';

    // Security event types
    case SUSPICIOUS_ACTIVITY = 'suspicious_activity';
    case BRUTE_FORCE_ATTEMPT = 'brute_force_attempt';
    case API_KEY_CREATED = 'api_key_created';
    case API_KEY_REVOKED = 'api_key_revoked';
    case TWO_FACTOR_ENABLED = 'two_factor_enabled';
    case TWO_FACTOR_DISABLED = 'two_factor_disabled';

    /**
     * Get the event type code (used in database)
     *
     * @return string The event type code
     */
    public function getCode(): string
    {
        return $this->value;
    }

    /**
     * Get the event type label (for display)
     *
     * @return string The human-readable label
     */
    public function getLabel(): string
    {
        return match ($this) {
            self::PASSWORD_CHANGE => 'Password Changed',
            self::PASSWORD_RESET_REQUEST => 'Password Reset Requested',
            self::PASSWORD_RESET_COMPLETE => 'Password Reset Completed',
            self::EMAIL_CHANGE => 'Email Changed',
            self::EMAIL_VERIFY => 'Email Verified',
            self::LOGIN_SUCCESS => 'Login Success',
            self::LOGIN_FAILURE => 'Login Failure',
            self::LOGOUT => 'Logout',
            self::ACCOUNT_LOCKED => 'Account Locked',
            self::REGISTRATION => 'Registration',
            self::ACCOUNT_ACTIVATE => 'Account Activated',
            self::ACCOUNT_DEACTIVATE => 'Account Deactivated',
            self::PROFILE_UPDATE => 'Profile Updated',
            self::ADMIN_LOGIN => 'Admin Login',
            self::ROLE_CHANGE => 'Role Changed',
            self::USER_IMPERSONATE => 'User Impersonated',
            self::SUSPICIOUS_ACTIVITY => 'Suspicious Activity',
            self::BRUTE_FORCE_ATTEMPT => 'Brute Force Attempt',
            self::API_KEY_CREATED => 'API Key Created',
            self::API_KEY_REVOKED => 'API Key Revoked',
            self::TWO_FACTOR_ENABLED => 'Two-Factor Authentication Enabled',
            self::TWO_FACTOR_DISABLED => 'Two-Factor Authentication Disabled',
            default => ucwords(str_replace('_', ' ', $this->value))
        };
    }

    /**
     * Get the icon class (FontAwesome)
     *
     * @return string The icon class
     */
    public function getIcon(): string
    {
        return match ($this) {
            self::PASSWORD_CHANGE,
            self::PASSWORD_RESET_REQUEST,
            self::PASSWORD_RESET_COMPLETE,
            self::API_KEY_CREATED,
            self::API_KEY_REVOKED => 'fa-key',

            self::EMAIL_CHANGE,
            self::EMAIL_VERIFY => 'fa-envelope',

            self::LOGIN_SUCCESS,
            self::ADMIN_LOGIN => 'fa-sign-in-alt',

            self::LOGOUT => 'fa-sign-out-alt',

            self::LOGIN_FAILURE => 'fa-times-circle',

            self::ACCOUNT_LOCKED => 'fa-lock',

            self::REGISTRATION => 'fa-user-plus',

            self::ACCOUNT_ACTIVATE => 'fa-user-check',

            self::ACCOUNT_DEACTIVATE => 'fa-user-slash',

            self::PROFILE_UPDATE => 'fa-user-edit',

            self::ROLE_CHANGE => 'fa-user-tag',

            self::USER_IMPERSONATE => 'fa-user-secret',

            self::SUSPICIOUS_ACTIVITY,
            self::BRUTE_FORCE_ATTEMPT => 'fa-exclamation-triangle',

            self::TWO_FACTOR_ENABLED,
            self::TWO_FACTOR_DISABLED => 'fa-shield-alt',

            default => 'fa-info-circle'
        };
    }

    /**
     * Get the CSS class (e.g. bootstrap bg-* classes)
     *
     * @return string The CSS class
     */
    public function getCssClass(): string
    {
        return match ($this->getSeverity()) {
            'critical' => 'bg-danger',
            'error' => 'bg-warning',
            'warning' => 'bg-warning',
            'success' => 'bg-success',
            default => 'bg-info'
        };
    }

    /**
     * Get the severity level (info, warning, error, critical)
     *
     * @return string The severity level
     */
    public function getSeverity(): string
    {
        return match ($this) {
            self::ACCOUNT_LOCKED,
            self::BRUTE_FORCE_ATTEMPT,
            self::SUSPICIOUS_ACTIVITY => 'critical',

            self::LOGIN_FAILURE => 'error',

            self::PASSWORD_RESET_REQUEST,
            self::ACCOUNT_DEACTIVATE,
            self::USER_IMPERSONATE,
            self::API_KEY_CREATED,
            self::API_KEY_REVOKED,
            self::TWO_FACTOR_DISABLED => 'warning',

            self::LOGIN_SUCCESS,
            self::REGISTRATION,
            self::ACCOUNT_ACTIVATE,
            self::EMAIL_VERIFY,
            self::TWO_FACTOR_ENABLED => 'success',

            default => 'info'
        };
    }

    /**
     * Check if this event type matches a given code
     *
     * @param string $code The code to check
     * @return bool True if this event type matches the code
     */
    public function is(string $code): bool
    {
        return $this->value === $code;
    }

    /**
     * Get all available event types as options for forms
     *
     * @return array<string, string> List of all event types as options
     */
    public static function getOptionsForForm(): array
    {
        $options = [];

        foreach (self::cases() as $case) {
            $options[$case->getLabel()] = $case->value;
        }

        return $options;
    }
}
