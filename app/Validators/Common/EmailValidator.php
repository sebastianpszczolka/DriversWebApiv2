<?php

declare(strict_types=1);

namespace App\Validators\Common;

use App\Validators\AbstractValidator;

class EmailValidator extends AbstractValidator
{
    public function __construct(array $data)
    {
        parent::__construct($data);

        $this->rules = [
            'email' => 'required|email'
        ];

        $this->messages = [
            'required' => trans('validation.is_required'),
            'email' => trans('validation.must_be_valid_email')
        ];
    }
}