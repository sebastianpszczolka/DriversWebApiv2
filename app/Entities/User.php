<?php

declare(strict_types=1);

namespace App\Entities;

use DateTime;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use JsonSerializable;
use Laravel\Passport\HasApiTokens;

/**
 * @property int $id
 * @property string $email
 * @property string $password
 * @property bool $activated
 * @property string|null $activation_code
 * @property DateTime|null $activated_at
 * @property DateTime|null $last_login
 * @property string|null $reset_password_code
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string|null $phone
 */
class User extends Authenticatable implements JsonSerializable
{
    use HasApiTokens;
    use Notifiable;

    protected $table = 'users';
    protected $hidden = [
        self::COLUMN_PASSWORD,
        self::COLUMN_RESET_PASSWORD_CODE,
        self::COLUMN_ACTIVATION_CODE,
        'remember_token' //could be removed???? 641
    ];
    protected $guarded = [
        self::COLUMN_RESET_PASSWORD_CODE,
        self::COLUMN_ACTIVATION_CODE
    ];
    protected $fillable = [
        self::COLUMN_EMAIL,
        self::COLUMN_PASSWORD,
        self::COLUMN_LAST_NAME,
        self::COLUMN_FIRST_NAME,
        self::COLUMN_PHONE,
        self::COLUMN_LAST_LOGIN,
        self::COLUMN_ACTIVATED,
        self::COLUMN_ACTIVATION_CODE,
        self::COLUMN_ACTIVATED_AT,
        self::COLUMN_RESET_PASSWORD_CODE,
    ];

    const TABLE = 'users';
    const COLUMN_ID = 'id';
    const COLUMN_EMAIL = 'email';
    const COLUMN_LAST_NAME = 'last_name';
    const COLUMN_FIRST_NAME = 'first_name';
    const COLUMN_PHONE = 'phone';
    const COLUMN_LAST_LOGIN = 'last_login';
    const COLUMN_PASSWORD = 'password';
    const COLUMN_ACTIVATED_AT = 'activated_at';
    const COLUMN_ACTIVATED = 'activated';
    const COLUMN_ACTIVATION_CODE = 'activation_code';
    const COLUMN_RESET_PASSWORD_CODE = 'reset_password_code';

    public function getAuthIdentifierName(): string
    {
        return 'id';
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    public function isActivated(): bool
    {
        return $this->activated;
    }

    public function getActivationCode(): ?string
    {
        return $this->activation_code;
    }

    public function getResetPasswordCode(): ?string
    {
        return $this->reset_password_code;
    }

    public function updateLastLogin(): void
    {
        $this->last_login = $this->freshTimestamp();
    }

    public function installations(): BelongsToMany
    {
        return $this->belongsToMany(Installation::class, InstallationUserRole::class);

    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'lastName' => $this->last_name,
            'firstName' => $this->first_name,
            'phone' => $this->phone,
        ];
    }
}
