<?php

namespace App\Entities;

use DateTime;
use Illuminate\Database\Eloquent\Model as BaseEntity;
use JsonSerializable;

/**
 * @property int $id
 * @property int $user_id
 * @property string $cfg
 * @property mixed $created_at
 * @property mixed $updated_at
 *
 */
class UserCfg extends BaseEntity implements JsonSerializable
{
    protected $table = self::TABLE;
    protected $fillable = [
        self::COLUMN_USER_ID,
        self::COLUMN_CFG
    ];

    const TABLE = 'users_cfg';

    const COLUMN_ID = 'id';
    const COLUMN_USER_ID = 'user_id';
    const COLUMN_CFG = 'cfg';
    const COLUMN_UPDATED_AT = 'updated_at';
    const COLUMN_CREATED_AT = 'created_at';

    public function getId(): int
    {
        return $this->id;
    }

    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function getCfg(): string
    {
        return $this->cfg;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->created_at;
    }

    public function getUpdatedAt(): DateTime
    {
        return $this->updated_at;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'user_id' => $this->getUserId(),
            'created_at' => $this->getCreatedAt(),
            'updated_at' => $this->getUpdatedAt(),
            'cfg' => json_decode($this->getCfg())
        ];
    }
}
