<?php
declare(strict_types=1);

namespace App\Repositories\Logs;

use App\Enum\LogsRangeModes;
use App\Exceptions\BaseException;
use App\Http\Requests\Installations\Logs\GetLogsRequestData;
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
                return "{$barcode}_{$logsParams->year}{$logsParams->month}{$logsParams->day}";
            case LogsRangeModes::WEEK_MODE:
                return "{$barcode}_{$logsParams->year}w{$logsParams->week}";
            case LogsRangeModes::MONTH_MODE:
                return "{$barcode}_{$logsParams->year}{$logsParams->month}";
            case LogsRangeModes::YEAR_MODE:
                return "{$barcode}_{$logsParams->year}";
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

        //choose file to process.
        if (file_exists($jsonFile)) {
            $realFilePath = $jsonFile;
        } else if (file_exists($sevenZipFile)) {
            $realFilePath = 'po rozkapkowaniua 7z';
        } else {
            throw new BaseException("No data in CONTROLLERS catalog {$jsonFile} or {$sevenZipFile}");
        }

        $commands = array_flip($commands);
        $result = [];

        try {
            $fHandle = fopen($realFilePath, 'r');

            while (!feof($fHandle)) {
                try {
                    $record = fgets($fHandle);
                    if (empty($record)) {
                        $this->logger->warning("prepareData->EmptyRecord in {$realFilePath}");
                        continue;
                    }

                    $recordParsed = json_decode($record, true);
                    if (is_null($recordParsed)) {
                        $this->logger->warning("prepareData->json_decode in {$record}");
                        continue;
                    }

                    $result[] = array_intersect_key($recordParsed, $commands);
                } catch (Throwable $e) {
                    $this->logger->warning("Exception->prepareData File: {$realFilePath}", [$e->getMessage()]);
                    throw new BaseException("Exception->prepareData File: {$realFilePath}");
                }
            }
        } finally {
            fclose($fHandle);
        }

        return $result;
    }
//    private function prepareData(string $filePath, string $fileDirPath): string
//    {
//        $jsonFile = "{$filePath}.json";
//        $sevenZipFile = "{$jsonFile}.7z";
//
//        $result = [];
//
//        // Helper variable for zipped file
//        $fileContent = null;
//
//        if (file_exists($jsonFile)) {
//            $fileContent = file_get_contents($jsonFile);
//        } else if (file_exists($sevenZipFile)) {
//            $archive = new CustomArchive7z($sevenZipFile);
//            $archive->setOutputDirectory($fileDirPath);
//            $archive->extract();
//
//            if (!file_exists($jsonFile)) {
//                throw new BaseException(static::class . '::prepareFile', "Unpacked file does not exists {$jsonFile}");
//            }
//
//            $fileContent = file_get_contents($jsonFile);
//            $removed = unlink($jsonFile);
//
//            if ($removed === false) {
//                Logger::log('Exception', "Cannot remove extracted file [{$jsonFile}]", static::class . '::prepareFile', 'high', 0);
//            }
//        } else {
//            throw new Exception('No data in CONTROLLERS catalog, ' . $jsonFile);
//        }
//
//        $records = explode("\r\n", $fileContent);
//
//        if (!(count($records) > 0)) {
//            return gzencode(json_encode($result), 9);
//        }
//
//        $extractedCommands = $this->extractCommands($chartLastChange);
//
//        foreach ($records as $record) {
//            $recordParsed = json_decode($record, true);
//            $recordResult = [];
//
//            if (is_null($recordParsed)) {
//                continue;
//            }
//
//            foreach ($extractedCommands as $extractedCommand) {
//                if (isset($recordParsed[$extractedCommand])) {
//                    $recordResult[$extractedCommand] = $recordParsed[$extractedCommand];
//                }
//            }
//
//            $result[] = $recordResult;
//        }
//
//        return $result;
//    }
}
