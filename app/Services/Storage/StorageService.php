<?php
declare(strict_types=1);

namespace App\Services\Storage;

use App\Dto\Storage\ReadDeviceDto;
use App\Dto\Storage\ReadMsgBoxDto;
use App\Dto\Storage\WriteDeviceDto;
use App\Dto\Storage\WriteDeviceNowDto;
use App\Http\Requests\Storage\ReadDeviceRequest;
use App\Http\Requests\Storage\ReadMsgBoxRequest;
use App\Http\Requests\Storage\WriteDeviceNowRequest;
use App\Http\Requests\Storage\WriteDeviceRequest;
use App\Libraries\Paths;
use App\Loggers\DefaultLogger;
use App\Repositories\Database\Objects\ObjectsRepository;
use App\Repositories\Storage\StorageRepository;
use App\Utils\CommonConst;
use App\Utils\PathHelper;
use DateTime;
use Exception;

class StorageService
{
    private StorageRepository $storeRepository;
    private ObjectsRepository $objectsRepository;
    private Paths $paths;
    private DefaultLogger $logger;


    /**
     * @param DefaultLogger $logger
     * @param ObjectsRepository $objectsRepository
     * @param StorageRepository $storeRepository
     * @param Paths $paths
     */
    public function __construct(DefaultLogger     $logger,
                                ObjectsRepository $objectsRepository,
                                StorageRepository $storeRepository,
                                Paths             $paths)
    {
        $this->logger = $logger;
        $this->objectsRepository = $objectsRepository;
        $this->storeRepository = $storeRepository;
        $this->paths = $paths;

    }

    public function readApl(array $data): array
    {
        // First Stage get value by keys from main storage
        $resultsStorage = $this->storeRepository->readApl($data);

        // Second Stage get value by missing keys in main storage
        $missedEntriesStorage = array_keys(array_filter($resultsStorage, function ($value) {
            return empty($value);
        }));

        if (empty($missedEntriesStorage)) {
            return $resultsStorage;
        }

        $resultsBackendStorage = $this->objectsRepository->getByKeys($missedEntriesStorage);
        foreach ($resultsBackendStorage as $resultSecondStorage) {
            $resultsStorage[$resultSecondStorage->getKey()] = json_decode($resultSecondStorage->getValue());
        }

        // Third Stage get value by missing keys but remove index node to make request more general
        $missedEntriesStorage = array_keys(array_filter($resultsStorage, function ($value) {
            return empty($value);
        }));

        if (empty($missedEntriesStorage)) {
            return $resultsStorage;
        }

        //fix array of missing entries to remove index node and look again in db for more general answer
        $missedEntriesStorageFixed = [];
        foreach ($missedEntriesStorage as $missedEntry) {
            try {
                [, $missedEntryFix] = explode('/', $missedEntry, 2);
                $missedEntriesStorageFixed["/{$missedEntryFix}"] = $missedEntry;
            } catch (Exception $e) {
                $this->logger->error($e->getMessage());
            }
        }

        $resultsBackendStorage = $this->objectsRepository->getByKeys(array_keys($missedEntriesStorageFixed));
        foreach ($resultsBackendStorage as $resultSecondStorage) {
            $resultsStorage[$missedEntriesStorageFixed[$resultSecondStorage->getKey()]] = json_decode($resultSecondStorage->getValue());
        }

        return $resultsStorage;
    }

    public function writeApl(array $data): array
    {
        return $this->storeRepository->writeApl($data);
    }

    public function readDev(ReadDeviceRequest $data): ReadDeviceDto
    {
        return $this->storeRepository->readDev($data);
    }

    public function writeDev(WriteDeviceRequest $data): WriteDeviceDto
    {
        return $this->storeRepository->writeDev($data);
    }

    public function readMsgBox(ReadMsgBoxRequest $data): ReadMsgBoxDto
    {
        return $this->storeRepository->readMsgBox($data);
    }

    /**
     * @param WriteDeviceNowRequest $data
     * @return WriteDeviceNowDto
     * @throws Exception
     */
    public function writeDevNow(WriteDeviceNowRequest $data): WriteDeviceNowDto
    {
        $commandsForLog = $this->storeRepository->getCommandsForInstallation((string)$data->Src->install, $data->var);

        $fileUserGiven = PathHelper::fixPathTraversal($data->Dst->fileName);
        $filePathInst = PathHelper::combine($this->paths->getInstBasePath((string)$data->Src->install), $fileUserGiven);

        PathHelper::createFileIfNotExist($filePathInst);
        $this->storeLog($filePathInst, new DateTime(), $commandsForLog);

        return new WriteDeviceNowDto([
            'Src' => ['Time' => time(), 'install' => $data->Src->install],
            'Dst' => ['Node' => $data->Dst->Node, 'install' => $data->Dst->install]
        ]);
    }

    private function storeLog(string $pathFileLog, DateTime $date, array $data): void
    {
        $data['date'] = $date->format(DATE_ATOM);
        file_put_contents($pathFileLog, json_encode($data, JSON_UNESCAPED_SLASHES) . CommonConst::EOL_LINE, LOCK_EX | FILE_APPEND);
    }

}
