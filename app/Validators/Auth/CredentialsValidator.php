<?php 

declare(strict_types=1);

namespace App\Validators\Auth;

use App\Validators\AbstractValidator;

class CredentialsValidator extends AbstractValidator
{
    public function __construct(array $data)
    {
        parent::__construct($data);

        $this->rules = [
            'email' => 'required|email',
            'password' => 'required',
        ];

        $this->messages = [
            'required' => trans('validation.is_required'),
            'email' => trans('validation.must_be_valid_email')
        ];
    }
}