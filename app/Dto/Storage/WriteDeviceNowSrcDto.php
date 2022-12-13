<?php

namespace App\Dto\Storage;

use Spatie\DataTransferObject\DataTransferObject;

class WriteDeviceNowSrcDto extends DataTransferObject
{
    public int $Time;
    public int $install;
}
