<?php

namespace App\Services\Exchange;

use App\Loggers\DefaultLogger;
use App\Services\Exchange\Utils\CommonConst;
use App\Services\Exchange\Utils\PathHelper;
use DateTime;
use Exception;

class LogCreator
{
    private DefaultLogger $logger;

    /**
     * The constructor.
     *
     * @param DefaultLogger $logger
     */
    public function __construct(DefaultLogger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * manage Log
     *
     * @param $ctrlPath string path to controllers
     * @param $node string node of driver
     * @param $linkName string link name to path
     * @param $aplGroup string application group
     * @param $sch string schema identifier
     * @param $data
     * @return void
     * @throws Exception
     */
    public function manageLog(string $ctrlPath,
                              string $node,
                              string $linkName,
                              string $aplGroup,
                              string $sch,
                              array  $data)
    {
        $date = new DateTime;
        $pathBase = PathHelper::combine($ctrlPath, $linkName, $aplGroup, "INST_{$node}", $sch, "LOG");
        //general path to directory with log for most logs file. Remove leading zero from month for compatibility.
        $pathDirLogGeneral = PathHelper::combine($pathBase, $date->format('Y'), ltrim($date->format('m'), '0'));
        //specific path to logs for week logs file
        $pathDirLogYearWeeks = PathHelper::combine($pathBase, $date->format('Y'));

        PathHelper::createDirectoryIfNotExist($pathBase);
        //hourly logs
        $this->manageGeneralLog($pathDirLogGeneral, $date, "{$node}_%Y%m%d%H.json", 60, $data);
        //daily logs
        $this->manageGeneralLog($pathDirLogGeneral, $date, "{$node}_%Y%m%d.json", 60, $data);
        //weekly logs
        $this->manageGeneralLog($pathDirLogYearWeeks, $date, "{$node}_%Yw%W.json", 10 * 60, $data);
        //monthly logs
        $this->manageGeneralLog($pathDirLogGeneral, $date, "{$node}_%Y%m.json", 30 * 60, $data);
        //yearly logs
        $this->manageGeneralLog($pathDirLogYearWeeks, $date, "{$node}_%Y.json", 6 * 60 * 60, $data);
    }

    /**
     * @param $pathLogDir string path to current directory with log
     * @param $date DateTime current datetime of log
     * @param $fileFormat string format log file
     * @param $interval int interval to check when create log
     * @param $data array data with params to log
     * @throws Exception
     */
    private function manageGeneralLog(string   $pathLogDir,
                                      DateTime $date,
                                      string   $fileFormat,
                                      int      $interval,
                                      array    $data): void
    {
        $timestamp = $date->getTimestamp();
        $pathFileLogs = PathHelper::combine($pathLogDir, strftime($fileFormat, $timestamp));

        PathHelper::createFileIfNotExist($pathFileLogs);

        if ($this->shouldAppendRecord($pathFileLogs, $timestamp, $interval)) {
            $this->storeLog($pathFileLogs, $date, $data);
        }
    }

    /**
     * @param $pathFileLog string path to log
     * @param $timeStamp int unix timestamp
     * @param $interval int interval to check if elapsed for log file [seconds]
     * @return bool
     * @throws Exception
     */
    private function shouldAppendRecord(string $pathFileLog, int $timeStamp, int $interval): bool
    {
        $mTime = filemtime($pathFileLog);
        if ($mTime === false) {
            $this->logger->error("Can not get modification time of path {$pathFileLog}");
            return false;
        }

        $this->logger->debug("Pathname: {$pathFileLog} TimeFile: {$mTime} TimeNow: {$timeStamp} Interval: {$interval}");
        return abs($timeStamp - $mTime) > $interval;
    }

    /**
     * @param $pathFileLog string path to log
     * @param $date DateTime current datetime of log
     * @param $data array data with params to log
     */
    private function storeLog(string $pathFileLog, DateTime $date, array $data): void
    {
        $data['date'] = $date->format(DATE_ATOM);
        file_put_contents($pathFileLog, json_encode($data, JSON_UNESCAPED_SLASHES) . CommonConst::EOL_LINE, LOCK_EX | FILE_APPEND);
    }
}
