<?php

namespace App\Validators\Installations\Resources;

use App\Validators\AbstractValidator;

class ListResourceValidator extends AbstractValidator
{
    public function __construct(array $data)
    {
        parent::__construct($data);

        $this->rules = [
            'folderPath' => 'string|required'
        ];

        $this->messages = [
            'string' => trans('validation.string'),
        ];
    }
}
