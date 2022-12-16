<?php

namespace App\Entities;

use DateTime;
use Illuminate\Database\Eloquent\Model as BaseEntity;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int $id
 * @property string $name
 * @property string $description
 * @property int $inst_barcode
 * @property DateTime $created_at
 */
class Installation extends BaseEntity
{
    protected $table = self::TABLE;
    protected $fillable = [
        self::COLUMN_NAME,
        self::COLUMN_DESCRIPTION,
        self::COLUMN_INSTALLATION_BARCODE
    ];
    const TABLE = 'installations';
    const COLUMN_ID = 'id';
    const COLUMN_NAME = 'name';
    const COLUMN_DESCRIPTION = 'description';
    const COLUMN_INSTALLATION_BARCODE = 'inst_barcode';

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name ?? '';
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getInstallationBarcode(): int
    {
        return $this->inst_barcode;
    }

    public function isAssignedToUser(User $user): bool
    {
        return $user->installations()->whereKey($this->getId())->exists();
    }

    public function users_roles(): BelongsToMany
    {
        return $this->belongsToMany(UserRole::class, InstallationUser::class);
    }

    public function jsonSerialize(): array
    {
        $user_role = $this->users_roles()->first();
        if (is_null($user_role)) {
            $user_role = [];
        }

        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'description' => $this->getDescription(),
            'installationBarcode' => $this->getInstallationBarcode(),
            'user_role' => $user_role,
        ];
    }
}
