<?php

namespace App\Dto\Storage;

use Spatie\DataTransferObject\DataTransferObject;

class WriteDeviceNowDto extends DataTransferObject
{
    public WriteDeviceNowSrcDto $Src;
    public WriteDeviceNowDstDto $Dst;
}
