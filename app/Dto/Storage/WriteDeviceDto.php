<?php

namespace App\Dto\Storage;

use Spatie\DataTransferObject\DataTransferObject;

class WriteDeviceDto extends DataTransferObject
{
    public WriteDeviceSrcDto $Src;
    public WriteDeviceDstDto $Dst;
}
