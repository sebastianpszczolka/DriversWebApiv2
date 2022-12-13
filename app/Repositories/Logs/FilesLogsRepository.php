<?php

namespace App\Repositories\Logs;

use App\Http\Requests\Installations\Logs\GetLogsRequestData;
use App\Repositories\Logs\Dto\LogsDataDto;
use App\Repositories\Logs\Dto\LogsGenerateParamsDto;
use App\Utils\CommonConst;

class FilesLogsRepository implements LogsRepository
{

    public function getLogs(GetLogsRequestData $logsParams, LogsGenerateParamsDto $generateParams): LogsDataDto
    {
        // TODO: Implement getLogs() method.

        return new LogsDataDto([]);
    }


    public function storeLog(string $pathFile, array $data): void
    {
        file_put_contents($pathFile, json_encode($data, JSON_UNESCAPED_SLASHES) . CommonConst::EOL_LINE, LOCK_EX | FILE_APPEND);
    }
}
