<?php
declare(strict_types=1);

namespace App\Services\Installations\Logs;

use App\Dto\Installations\Logs\LogsResponseDto;
use App\Entities\Installation;
use App\Entities\User;
use App\Enum\LogsRangeModes;
use App\Exceptions\InstallationNotAssignedException;
use App\Http\Requests\Installations\Logs\GetLogsRequestData;
use App\Http\Requests\Installations\Logs\LogsParamsRequestData;
use App\Repositories\Installation\InstallationStatusRepository;
use App\Repositories\Logs\LogsRepository;


class LogsService
{
    protected LogsRepository $logsRepository;
    private InstallationStatusRepository $installationStatusRepository;

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

        $mode = $this->determineMode($params);






//        echo($this->installationStatusRepository->getSchemaNoByInstallationBarcode($installation->getInstallationBarcode()));

//        /** @var InstallationDeviceAssignment | null $deviceAssignment */
//        $deviceAssignment = $installation->device()->first();
//
//        if (is_null($deviceAssignment)) {
//            throw new InstallationNotFoundException();
//        }
//
//        $chart = $this->chartsService->getById($params->chartId);
//
//        $schemaNo = $this->varsRepository->getSchemaNoByDeviceBarcode($deviceAssignment->getDeviceBarcode());
//        $schema = $this->schemaService->getByNo($schemaNo);
//        /** @var ChartLastChange $chartLastChange */
//        $chartLastChange = $schema->chartLastChange()->first();
//

//
//        $deviceBarcode = $deviceAssignment->getDeviceBarcode();
//        $dateFromParams = $this->makeDateFromParams($mode, $params->year, $params->week, $params->day);
//        $cache = $this->getCacheInfo($dateFromParams);
//
//        $headers = [
//            'Content-Encoding' => 'gzip',
//            'Content-Type' => 'application/json',
//        ];
//
//        if (isset($cache->expire)) {
//            $headers = array_merge($headers, [
//                'Cache-Control' => 'public',
//                'Expires' => $cache->expire,
//            ]);
//        } else {
//            $headers = array_merge($headers, [
//                'Cache-Control' => "public, max-age=$cache->age",
//            ]);
//        }
//
//        $logsData = $this->logsRepository->getLogs(
//            $params,
//            new LogsGenerateParamsDto(
//                $mode,
//                $deviceBarcode,
//                $chart->getType(),
//                $chart->getRole(),
//                $this->getCommandsList($chartLastChange)
//            )
//        );

//        return new LogsResponseDto(
//            $headers,
//            gzencode(json_encode($logsData->getData()), 9)
//        );


//        return new LogsResponseDto(['data' => gzencode(json_encode([]), 9), 'headers' => $headers]);
        return new LogsResponseDto(['data' => json_encode([]), 'headers' => $headers]);
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
