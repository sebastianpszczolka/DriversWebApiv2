<?php
declare(strict_types=1);

namespace App\Validators\Auth\Registration;

use App\Validators\AbstractValidator;

class RegisterAccountDataValidator extends AbstractValidator
{
    public function __construct(array $data)
    {
        parent::__construct($data);

        $this->rules = [
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'terms' => 'accepted'
        ];

        $this->messages = [
            'required' => trans('validation.is_required'),
            'email' => trans('validation.must_be_valid_email'),
            'confirmed' => trans('validation.password_confirm_mismatch'),
            'min' => trans('validation.password_must_be_at_least_x_characters_long', ['chars' => '8']),
            'unique' => trans('validation.email_is_taken'),
            'string' => trans('validation.string')
        ];
    }
}
