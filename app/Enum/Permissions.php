<?php

declare(strict_types=1);

namespace App\Enum;

/**
 * Describe permissions in app
 */
class Permissions
{
    /** @var string User is normal (regular) user */
    public const USER = 'user';

    /** @var string User is administrator and has a lot of permissions */
    public const ADMIN = 'admin';

    /**
     * Return all permissions keys
     */
    public static function getAllPermissions(): array
    {
        return [
            Permissions::USER,
            Permissions::ADMIN
        ];
    }
}
