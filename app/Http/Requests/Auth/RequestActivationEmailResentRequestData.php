<?php

declare(strict_types=1);

namespace App\Http\Requests\Auth;

use Spatie\DataTransferObject\FlexibleDataTransferObject;

class RequestActivationEmailResentRequestData extends FlexibleDataTransferObject
{
    public string $email;
}
