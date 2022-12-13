<?php

namespace App\Dto\Storage;

use Spatie\DataTransferObject\DataTransferObject;

class WriteDeviceNowDstDto extends DataTransferObject
{
    public int $Node;
    public int $install;
}
