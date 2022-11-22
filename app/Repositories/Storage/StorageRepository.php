<?php

namespace App\Repositories\Storage;

interface StorageRepository
{
    public function readApl(array $data): array;

    public function writeApl(array $data): array;

    public function readDev(array $data): array;

    public function writeDev(array $data): array;

    public function readMsgBox(array $data): array;
}
