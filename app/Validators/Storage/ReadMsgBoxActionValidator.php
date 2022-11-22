<?php

namespace App\Validators\Storage;

use App\Validators\AbstractValidator;

class ReadMsgBoxActionValidator extends AbstractValidator
{
    public function __construct(array $data)
    {
        parent::__construct($data);

        $this->rules = [
            'Src' => 'required|array',
            'Src.Node' => 'required|integer',
            'Src.Ucsn' => 'required|integer',
            'Src.Time' => 'required|integer',
            'Src.install' => 'required|integer',
            'Dst' => 'required|array',
            'Dst.Node' => 'required|integer',
            'Dst.install' => 'required|integer',
            'InpBox' => 'required|array'
        ];

        $this->messages = [
            'required' => trans('validation.is_required'),
            'array' => trans('validation.is_array_required'),
            'integer' => trans('validation.must_be_integer'),
        ];
    }


}
