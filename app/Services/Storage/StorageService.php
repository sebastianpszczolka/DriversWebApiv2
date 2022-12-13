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
use App\Repositories\Storage\StorageRepository;
use App\Utils\CommonConst;
use App\Utils\PathHelper;
use DateTime;
use Exception;

class StorageService
{
    private StorageRepository $storeRepository;
    private Paths $paths;

    /**
     * @param StorageRepository $storeRepository
     * @param Paths $paths
     */
    public function __construct(StorageRepository $storeRepository, Paths $paths)
    {
        $this->storeRepository = $storeRepository;
        $this->paths = $paths;
    }

    public function readApl(array $data): array
    {
        return $this->storeRepository->readApl($data);
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

        $filePathInst = PathHelper::fixPathTraversal(PathHelper::combine($this->paths->getInstBasePath((string)$data->Src->install),
            $data->Dst->fileName));

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
