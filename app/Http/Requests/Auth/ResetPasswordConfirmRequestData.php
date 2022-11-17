<?php

declare(strict_types=1);

namespace App\Http\Requests\Auth;

use Spatie\DataTransferObject\DataTransferObject;

class ResetPasswordConfirmRequestData extends DataTransferObject
{
    public string $newPassword;
    public string $newPasswordConfirm;
}