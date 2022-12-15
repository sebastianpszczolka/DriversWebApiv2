<?php
declare(strict_types=1);

namespace App\Repositories\Logs;

use App\Enum\LogsRangeModes;
use App\Exceptions\BaseException;
use App\Http\Requests\Installations\Logs\GetLogsRequestData;
use App\Libraries\CustomArchive7z;
use App\Libraries\Paths;
use App\Loggers\DefaultLogger;
use App\Repositories\Logs\Dto\LogsDataDto;
use App\Repositories\Logs\Dto\LogsGenerateParamsDto;
use App\Utils\PathHelper;
use Exception;
use Illuminate\Support\Str;
use Throwable;


class FilesLogsRepository implements LogsRepository
{
    private Paths $paths;
    const NAME_LOG = 'LOG';
    private DefaultLogger $logger;

    /**
     * @param Paths $paths
     * @param DefaultLogger $logger
     */
    public function __construct(Paths $paths, DefaultLogger $logger)
    {
        $this->paths = $paths;
        $this->logger = $logger;
    }

    /**
     * @throws Exception
     */
    public function getLogs(GetLogsRequestData $logsParams, LogsGenerateParamsDto $generateParams): LogsDataDto
    {
        $logBasePath = PathHelper::combine($this->paths->getInstBasePath($generateParams->getInstBarcode()), static::NAME_LOG);

        $folderPath = $this->getFileDir($logBasePath, $generateParams->getMode(), $logsParams);
        $fileName = $this->getFileName($generateParams->getMode(), $generateParams->getInstBarcode(), $logsParams);

        return new LogsDataDto($this->prepareData($fileName, $folderPath, $logsParams->commands));
    }

    /**
     * @param string $basePath
     * @param $mode
     * @param GetLogsRequestData $logsParams
     * @return string
     * @throws Exception
     */
    private static function getFileDir(string $basePath, $mode, GetLogsRequestData $logsParams): string
    {
        $month = $logsParams->month;
        if (Str::startsWith($month, '0')) {
            $month = ltrim((string)$month, '0');
        }

        switch ($mode) {
            case LogsRangeModes::DAY_MODE:
            case LogsRangeModes::MONTH_MODE:
                return PathHelper::combine($basePath, $logsParams->year, $month);
            case LogsRangeModes::WEEK_MODE:
            case LogsRangeModes::YEAR_MODE:
                return PathHelper::combine($basePath, $logsParams->year);
            default:
                throw new Exception('Unknown logs mode: ' . $mode);
        }
    }

    /**
     * @param string $mode
     * @param string $barcode
     * @param GetLogsRequestData $logsParams
     * @return string
     * @throws Exception
     */
    private static function getFileName(string $mode, string $barcode, GetLogsRequestData $logsParams): string
    {
        switch ($mode) {
            case LogsRangeModes::DAY_MODE:
                return sprintf('%s_%d%02d%02d', $barcode, $logsParams->year, $logsParams->month, $logsParams->day);
            case LogsRangeModes::WEEK_MODE:
                return sprintf('%s_%dw%02d', $barcode, $logsParams->year, $logsParams->week);
            case LogsRangeModes::MONTH_MODE:
                return sprintf('%s_%d%02d', $barcode, $logsParams->year, $logsParams->month);
            case LogsRangeModes::YEAR_MODE:
                return sprintf('%s_%d', $barcode, $logsParams->year);
            default:
                throw new Exception('Unknown logs mode: ' . $mode);
        }
    }

    /**
     * @throws Exception
     */
    private function prepareData(string $fileName, string $folderPath, array $commands): array
    {
        $jsonFile = PathHelper::combine($folderPath, "{$fileName}.json");
        $sevenZipFile = "{$jsonFile}.7z";

        if (file_exists($jsonFile)) {
            $result = $this->getCommandsFromFile($jsonFile, $commands);

        } else if (file_exists($sevenZipFile)) {
            $archive = new CustomArchive7z($sevenZipFile, null, 120);
            $archive->setOutputDirectory($folderPath);
            $archive->extract();

            if (!file_exists($jsonFile)) {
                throw new BaseException(static::class . '::prepareData', "Unpacked file does not exists {$jsonFile}");
            }

            $result = $this->getCommandsFromFile($jsonFile, $commands);
            $removed = unlink($jsonFile);

            if ($removed === false) {
                $this->logger->warning("Cannot remove extracted file {$jsonFile}");
            }

        } else {
            throw new BaseException(static::class . '::prepareData', "No data in CONTROLLERS catalog {$jsonFile} or {$sevenZipFile}");
        }

        return $result;
    }

    /**
     * @param string $filePath
     * @param array $commands
     * @return array
     * @throws BaseException
     */
    private function getCommandsFromFile(string $filePath, array $commands): array
    {
        $commands = array_flip($commands);
        $result = [];

        try {
            $fHandle = fopen($filePath, 'r');

            while (!feof($fHandle)) {
                try {
                    $record = fgets($fHandle);
                    if (empty($record)) {
                        $this->logger->warning("prepareData->EmptyRecord in {$filePath}");
                        continue;
                    }

                    $recordParsed = json_decode($record, true);
                    if (is_null($recordParsed)) {
                        $this->logger->warning("prepareData->json_decode in {$record}");
                        continue;
                    }
                    $result[] = array_intersect_key($recordParsed, $commands);
                } catch (Throwable $e) {
                    $this->logger->warning("Exception->prepareData File: {$filePath}", [$e->getMessage()]);
                    throw new BaseException(static::class . '::prepareData', "prepareData File: {$filePath}");
                }
            }
        } finally {
            fclose($fHandle);
        }

        return $result;
    }
}
