<?php

namespace App\Http\Requests\Installations\Logs;

use Spatie\DataTransferObject\FlexibleDataTransferObject;

class LogsParamsRequestData extends FlexibleDataTransferObject
{
    public int $year;
    public ?int $month;
    public ?int $week;
    public ?int $day;
}
