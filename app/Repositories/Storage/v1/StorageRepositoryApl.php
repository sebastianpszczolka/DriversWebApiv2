<?php

namespace App\Repositories\Storage\v1;

use App\Http\Requests\Installations\v1\Storage\ReadAplRequest;
use App\Http\Requests\Installations\v1\Storage\WriteAplRequest;

interface StorageRepositoryApl
{
    public function readApl(ReadAplRequest $data, int $node): array;

    public function writeApl(WriteAplRequest $data, int $node): array;
}
