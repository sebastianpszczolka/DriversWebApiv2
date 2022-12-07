<?php

namespace App\Validators\Installations\Resources;

use App\Validators\AbstractValidator;

class ReadResourceValidator extends AbstractValidator
{
    public function __construct(array $data)
    {
        parent::__construct($data);

        $this->rules = [
            'filePath' => 'string|required_without:folderPath',
            'folderPath' => 'string|required_without:filePath'
        ];

        $this->messages = [
            'string' => trans('validation.string'),
        ];
    }
}

