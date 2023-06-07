<?php

declare(strict_types=1);

namespace App\Services\Storage\v1;

use App\Http\Requests\Installations\v1\Storage\ReadAplRequest;
use App\Http\Requests\Installations\v1\Storage\WriteAplRequest;
use App\Loggers\DefaultLogger;
use App\Repositories\Storage\v1\StorageRepositoryApl;

class StorageService
{

    private StorageRepositoryApl $storageRepositoryApl;
    private DefaultLogger $logger;

    /**
     * @param DefaultLogger $logger
     * @param StorageRepositoryApl $storageRepositoryApl
     */
    public function __construct(DefaultLogger $logger, StorageRepositoryApl $storageRepositoryApl)
    {
        $this->logger = $logger;
        $this->storageRepositoryApl = $storageRepositoryApl;
    }

    public function readApl(ReadAplRequest $data, int $node): array
    {
        return $this->storageRepositoryApl->readApl($data, $node);
    }

    public function writeApl(WriteAplRequest $data, int $node): array
    {
        return $this->storageRepositoryApl->writeApl($data, $node);
    }
}
