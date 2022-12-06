<?php

namespace App\Validators\Installations\Logs;

use App\Validators\AbstractValidator;
use Carbon\Carbon;

class LogsParamsValidator extends AbstractValidator
{
    public function __construct(array $data)
    {
        parent::__construct($data);

        $maxYear = (Carbon::now()->year + 2);

        $this->rules = [
            'year' => sprintf('integer|required|max:%d', $maxYear),
            'week' => 'integer|between:1,54',
            'month' => 'integer|between:1,12',
            'day' => 'integer|between:1,31',
        ];

        $this->messages = [
            'integer' => trans('validation.value_must_be_numeric'),
            'between' => trans('validation.number_must_be_between'),
        ];
    }

}
