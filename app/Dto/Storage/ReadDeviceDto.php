<?php

namespace App\Dto\Storage;

use Spatie\DataTransferObject\DataTransferObject;

class ReadDeviceDto extends DataTransferObject
{
    public ReadDeviceSrcDto $Src;
    public ReadDeviceDstDto $Dst;

    public array $var;

}
