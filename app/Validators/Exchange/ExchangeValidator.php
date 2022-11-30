<?php
declare(strict_types=1);

namespace App\Validators\Exchange;

use App\Validators\AbstractValidator;

class ExchangeValidator extends AbstractValidator
{
    public function __construct(array $data)
    {
        parent::__construct($data);

        $this->rules = [
            '/DNET/MST/LINK/NAME' => 'required|string',
            '/DNET/HOST/APLGROUP' => 'required|string',
            '/DNET/HOST/SCH' => 'required|string',
            '/DNET/HOST/NODE' => 'required|string',
            '/DNET/HOST/UCSN' => 'required|string',
            '/DNET/MST/STORAGE/FILE/NAME' => 'required|string',
             'data' => 'required|array'
        ];

        $this->messages = [
            'required' => trans('validation.is_required'),
            'string' => trans('validation.string'),
            'array' => trans('validation.is_array_required'),
        ];
    }
}
