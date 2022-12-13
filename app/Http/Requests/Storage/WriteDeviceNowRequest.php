<?php
declare(strict_types=1);

namespace App\Http\Requests\Storage;

use Spatie\DataTransferObject\FlexibleDataTransferObject;

class WriteDeviceNowRequest extends FlexibleDataTransferObject
{
    public WriteDeviceNowSrcRequest $Src;
    public WriteDeviceNowDstRequest $Dst;
    public array $var;
}
