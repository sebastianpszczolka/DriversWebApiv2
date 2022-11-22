<?php

namespace App\Services\Storage;

use App\Repositories\Storage\StorageRepository;

class StorageService
{
    private StorageRepository $storeRepository;

    public function __construct(StorageRepository $storeRepository)
    {
        $this->storeRepository = $storeRepository;
    }

    public function readApl(array $data): array
    {
        return $this->storeRepository->readApl($data);
    }

    public function writeApl(array $data): array
    {
        return $this->storeRepository->writeApl($data);
    }

    public function readDev(array $data): array
    {
        return $this->storeRepository->readDev($data);
    }

    public function writeDev(array $data): array
    {
        return $this->storeRepository->writeDev($data);
    }

    public function readMsgBox(array $data): array
    {
        return $this->storeRepository->readMsgBox($data);
    }

}
