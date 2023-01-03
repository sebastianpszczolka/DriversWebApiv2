<?php
declare(strict_types=1);

namespace App\Entities;

use Illuminate\Database\Eloquent\Model as BaseEntity;

class InstallationUserRole extends BaseEntity
{
    protected $table = self::TABLE;
    protected $fillable = [
        self::COLUMN_USER_ID,
        self::COLUMN_INSTALLATION_ID,
        self::COLUMN_USER_ROLE_ID
    ];

    const TABLE = 'installations_users_roles';
    const COLUMN_USER_ID = 'user_id';
    const COLUMN_INSTALLATION_ID = 'installation_id';
    const COLUMN_USER_ROLE_ID = 'user_role_id';
}
