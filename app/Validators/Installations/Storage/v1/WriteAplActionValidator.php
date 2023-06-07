<?php

namespace App\Validators\Installations\Storage\v1;

use App\Validators\AbstractValidator;

class WriteAplActionValidator extends AbstractValidator
{
    public function __construct(array $data)
    {
        parent::__construct($data);

        $this->rules = [
            'expired' => 'required|integer',
            'variables' => 'required|array'
        ];

        $this->messages = [
            'required' => trans('validation.is_required'),
            'array' => trans('validation.is_array_required'),
            'integer' => trans('validation.must_be_integer')
        ];
    }

}



