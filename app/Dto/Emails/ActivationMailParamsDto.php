<?php

declare(strict_types=1);

namespace App\Dto\Emails;

use Spatie\DataTransferObject\DataTransferObject;

class ActivationMailParamsDto extends DataTransferObject
{
    public string $email;
    public int $userId;
    public string $activationCode;
}
