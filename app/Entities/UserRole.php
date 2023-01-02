<?php
declare(strict_types=1);

namespace App\Entities;

use Illuminate\Database\Eloquent\Model as BaseEntity;

/**
 * @property mixed $id
 * @property mixed $description
 * @property mixed $role
 */
class UserRole extends BaseEntity
{
    protected $table = self::TABLE;
    protected $fillable = [
        self::COLUMN_ROLE,
        self::COLUMN_DESCRIPTION
    ];
    const TABLE = 'users_roles';

    const COLUMN_ID = 'id';
    const COLUMN_ROLE = 'role';
    const COLUMN_DESCRIPTION = 'description';

    public function getId(): int
    {
        return $this->id;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'role' => $this->getRole(),
            'description' => $this->getDescription()
        ];
    }
}
