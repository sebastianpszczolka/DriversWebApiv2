<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model as BaseEntity;

class UserGroup extends BaseEntity
{
    protected $table = self::TABLE;
    protected $fillable = [
        self::COLUMN_USER_ID,
        self::COLUMN_GROUP_ID
    ];

    const TABLE = 'users_groups';

    const COLUMN_ID = 'id';
    const COLUMN_USER_ID = 'user_id';
    const COLUMN_GROUP_ID = 'group_id';


}
