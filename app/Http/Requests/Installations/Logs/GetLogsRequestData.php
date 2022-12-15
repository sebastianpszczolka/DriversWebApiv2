<?php

namespace App\Http\Requests\Installations\Logs;

use Spatie\DataTransferObject\FlexibleDataTransferObject;

class GetLogsRequestData extends FlexibleDataTransferObject
{
    public int $year;
    public ?int $month;
    public ?int $week;
    public ?int $day;

    public array $commands;
}
