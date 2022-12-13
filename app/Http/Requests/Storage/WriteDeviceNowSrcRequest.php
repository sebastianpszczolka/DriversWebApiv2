<?php
declare(strict_types=1);

namespace App\Http\Requests\Storage;

use Spatie\DataTransferObject\FlexibleDataTransferObject;

class WriteDeviceNowSrcRequest extends FlexibleDataTransferObject
{
    public int $Node;
    public int $Ucsn;
    public int $Time;
    public int $install;

}
