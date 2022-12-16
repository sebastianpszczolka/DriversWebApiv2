<?php
declare(strict_types=1);

namespace App\Entities;

use Illuminate\Database\Eloquent\Model as BaseEntity;

class InstallationUser extends BaseEntity
{
    protected $table = self::TABLE;
    protected $fillable = [
        'user_id',
        'installation_id',
        'user_role_id'
    ];

    const TABLE = 'installations_users';
    const COLUMN_USER_ID = 'user_id';
    const COLUMN_INSTALLATION_ID = 'installation_id';

    const COLUMN_USER_ROLE_ID = 'user_role_id';
}
