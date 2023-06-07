<?php

namespace App\Validators\Installations\Storage\v1;

use App\Validators\AbstractValidator;

class ReadAplActionValidator extends AbstractValidator
{
    public function __construct(array $data)
    {
        parent::__construct($data);

        $this->rules = [
            'members' => 'present|array',
            'variables' => 'required|array'
        ];

        $this->messages = [
            'required' => trans('validation.is_required'),
            'present' => trans('validation.is_required'),
            'array' => trans('validation.is_array_required'),
        ];
    }

}

