<?php

declare(strict_types=1);

namespace App\Dto\Auth;

use Spatie\DataTransferObject\DataTransferObject;

class AuthenticatedUserDto extends DataTransferObject
{
    public array $user;
    public string $token;
}