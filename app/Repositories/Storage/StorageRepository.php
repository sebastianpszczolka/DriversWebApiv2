<?php

namespace App\Repositories\Storage;

use App\Dto\Storage\ReadMsgBoxDto;
use App\Dto\Storage\WriteDeviceDto;
use App\Http\Requests\Storage\ReadMsgBoxRequest;
use App\Http\Requests\Storage\WriteDeviceRequest;

interface StorageRepository
{
    public function readApl(array $data): array;

    public function writeApl(array $data): array;

    public function readDev(array $data): array;

    public function writeDev(WriteDeviceRequest $data): WriteDeviceDto;

    public function readMsgBox(ReadMsgBoxRequest $data): ReadMsgBoxDto;
}
