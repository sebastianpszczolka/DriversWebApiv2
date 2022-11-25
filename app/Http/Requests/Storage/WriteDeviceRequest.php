<?php

namespace App\Http\Requests\Storage;

use Spatie\DataTransferObject\FlexibleDataTransferObject;

class WriteDeviceRequest extends FlexibleDataTransferObject
{
    public WriteDeviceSrcRequest $Src;
    public WriteDeviceDstRequest $Dst;

    public array $var;
}
