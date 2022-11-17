<?php

declare(strict_types=1);

namespace App\Http\Requests\Auth;

use Spatie\DataTransferObject\DataTransferObject;

class RequestResetPasswordRequestData extends DataTransferObject
{
    public string $email;
}