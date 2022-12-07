<?php

namespace App\Repositories\Logs;

use App\Http\Requests\Installations\Logs\GetLogsRequestData;
use App\Repositories\Logs\Dto\LogsDataDto;
use App\Repositories\Logs\Dto\LogsGenerateParamsDto;

class FilesLogsRepository implements LogsRepository
{

    public function getLogs(GetLogsRequestData $logsParams, LogsGenerateParamsDto $generateParams): LogsDataDto
    {
        // TODO: Implement getLogs() method.

        return new LogsDataDto([]);
    }
}
