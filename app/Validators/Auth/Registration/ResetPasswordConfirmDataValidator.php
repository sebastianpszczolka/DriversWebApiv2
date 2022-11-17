<?php

declare(strict_types=1);

namespace App\Validators\Auth\Registration;

use App\Validators\AbstractValidator;

class ResetPasswordConfirmDataValidator extends AbstractValidator
{
    public function __construct(array $data)
    {
        parent::__construct($data);

        $this->rules = [
            'newPassword' => 'required|min:8',
            'newPasswordConfirm' => 'required|same:newPasswordConfirm',
        ];

        $this->messages = [
            'required' => trans('validation.is_required'),
            'same' => trans('validation.password_confirm_mismatch'),
            'min' => trans('validation.password_must_be_at_least_x_characters_long', ['chars' => '8']),
        ];
    }
}