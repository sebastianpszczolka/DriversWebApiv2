<?php

declare(strict_types=1);

namespace App\Http\Requests\Auth;

use Spatie\DataTransferObject\FlexibleDataTransferObject;

class CreateAccountRequestData extends FlexibleDataTransferObject
{
    public bool $terms;
    public string $email;
    public string $password;
}
