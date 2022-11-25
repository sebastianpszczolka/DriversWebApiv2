<?php

namespace App\Http\Requests\Storage;

use Spatie\DataTransferObject\FlexibleDataTransferObject;

class ReadDeviceRequest extends FlexibleDataTransferObject
{
    public ReadDeviceSrcRequest $Src;
    public ReadDeviceDstRequest $Dst;

    public array $var;
}


