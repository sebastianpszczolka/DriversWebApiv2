<?php
declare(strict_types=1);

namespace App\Services\Installations\Logs;

use App\Dto\Installations\Logs\LogsResponseDto;
use App\Entities\Installation;
use App\Entities\User;
use App\Enum\LogsRangeModes;
use App\Exceptions\InstallationNotAssignedException;
use App\Exceptions\InstallationNotFoundException;
use App\Http\Requests\Installations\Logs\GetLogsRequestData;
use App\Libraries\Paths;
use App\Repositories\Installation\InstallationStatusRepository;
use App\Repositories\Logs\Dto\LogsGenerateParamsDto;
use App\Repositories\Logs\LogsRepository;


class LogsService
{
    protected LogsRepository $logsRepository;
    protected InstallationStatusRepository $installationStatusRepository;

    public function __construct(LogsRepository               $logsRepository,
                                InstallationStatusRepository $installationStatusRepository)
    {
        $this->logsRepository = $logsRepository;
        $this->installationStatusRepository = $installationStatusRepository;
    }

    /**
     * @param User $user
     * @param Installation $installation
     * @param GetLogsRequestData $params
     * @return LogsResponseDto
     * @throws InstallationNotAssignedException
     */
    public function getLogs(User $user, Installation $installation, GetLogsRequestData $params): LogsResponseDto
    {
        if ($installation->isAssignedToUser($user) === false) {
            throw new InstallationNotAssignedException();
        }

        $headers = [
            'Content-Encoding' => 'gzip',
            'Content-Type' => 'application/json',
        ];

        $instBarcode = (string)$installation->getInstallationBarcode();
        $mode = $this->determineMode($params);
        $logsData = $this->logsRepository->getLogs($params, new LogsGenerateParamsDto($mode, $instBarcode));

//        return new LogsResponseDto(
//            ['data' => json_encode($logsData->getData()), 'headers' => $headers]
//        );
        return new LogsResponseDto(
            ['data' => gzencode(json_encode($logsData->getData()), 9), 'headers' => $headers]
        );
    }

    private function determineMode(GetLogsRequestData $params): string
    {
        if (!is_null($params->day)) {
            return LogsRangeModes::DAY_MODE;
        }

        if (!is_null($params->week)) {
            return LogsRangeModes::WEEK_MODE;
        }

        if (!is_null($params->month)) {
            return LogsRangeModes::MONTH_MODE;
        }

        return LogsRangeModes::YEAR_MODE;
    }
}
