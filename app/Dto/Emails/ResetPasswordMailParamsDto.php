<?php

declare(strict_types=1);

namespace App\Dto\Emails;

use Spatie\DataTransferObject\DataTransferObject;

class ResetPasswordMailParamsDto extends DataTransferObject
{
    public string $lang;
    public string $email;
    public int $userId;
    public string $passwordResetCode;
}
