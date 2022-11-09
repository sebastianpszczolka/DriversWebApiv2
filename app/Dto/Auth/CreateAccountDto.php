<?php

declare(strict_types=1);

namespace App\Dto\Auth;

use Spatie\DataTransferObject\FlexibleDataTransferObject;

class CreateAccountDto extends FlexibleDataTransferObject
{
    public string $email;
    public string $password;
}
