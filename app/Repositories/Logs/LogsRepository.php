<?php
declare(strict_types=1);

namespace App\Repositories\Logs;

use App\Http\Requests\Installations\Logs\GetLogsRequestData;
use App\Repositories\Logs\Dto\LogsDataDto;
use App\Repositories\Logs\Dto\LogsGenerateParamsDto;


interface LogsRepository
{
    public function getLogs(GetLogsRequestData $logsParams, LogsGenerateParamsDto $generateParams): LogsDataDto;

}
