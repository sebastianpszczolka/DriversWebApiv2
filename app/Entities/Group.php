<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model as BaseEntity;

/**
 * @property int $id
 * @property string $name
 * @property string $permissions
 */
class Group extends BaseEntity
{
    protected $table = self::TABLE;
    protected $fillable = [
        self::COLUMN_NAME,
        self::COLUMN_PERMISSIONS
    ];
    const TABLE = 'groups';
    const COLUMN_ID = 'id';
    const COLUMN_NAME = 'name';
    const COLUMN_PERMISSIONS = 'permissions';

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPermissions(): array
    {
        return json_decode($this->permissions, true);
    }

    public function setPermissions(array $permissions): void
    {
        $this->permissions = json_encode($permissions);
    }
}
