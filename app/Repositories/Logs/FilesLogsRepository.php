<?php

namespace App\Repositories\Logs;

use App\Http\Requests\Installations\Logs\LogsParamsRequestData;
use App\Repositories\Logs\Dto\LogsDataDto;
use App\Repositories\Logs\Dto\LogsGenerateParamsDto;

class FilesLogsRepository implements LogsRepository
{

    public function getLogs(LogsParamsRequestData $logsParams, LogsGenerateParamsDto $generateParams): LogsDataDto
    {
        // TODO: Implement getLogs() method.

        return new LogsDataDto([]);
    }
}
