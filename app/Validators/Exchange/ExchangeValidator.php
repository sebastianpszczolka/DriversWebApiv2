<?php
declare(strict_types=1);

namespace App\Validators\Exchange;

use App\Services\Exchange\Utils\Commands;
use App\Validators\AbstractValidator;

class ExchangeValidator extends AbstractValidator
{
    public function __construct(array $data)
    {
        parent::__construct($data);

        $this->rules = [
            Commands::LINK_NAME => 'required|string',
            Commands::APLGROUP => 'required|string',
            Commands::SCH => 'required|string',
            Commands::NODE => 'required|string',
            Commands::UCSN => 'required|string',
            Commands::FILE_NAME => 'required|string',
             'data' => 'required|array'
        ];

        $this->messages = [
            'required' => trans('validation.is_required'),
            'string' => trans('validation.string'),
            'array' => trans('validation.is_array_required'),
        ];
    }
}
