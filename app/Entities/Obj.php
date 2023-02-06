<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model as BaseEntity;

/**
 * @property $id
 * @property string $key
 * @property string $value
 */
class Obj extends BaseEntity
{
    protected $table = self::TABLE;
    protected $fillable = [
        self::COLUMN_KEY,
        self::COLUMN_VALUE
    ];
    const TABLE = 'objects';

    const COLUMN_ID = 'id';

    const COLUMN_KEY = 'key';
    const COLUMN_VALUE = 'value';

    public function getId(): int
    {
        return $this->id;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function getValue(): string
    {
        return $this->value;
    }

}
