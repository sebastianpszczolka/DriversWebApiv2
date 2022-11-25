<?php

namespace App\Http\Requests\Storage;

use Spatie\DataTransferObject\FlexibleDataTransferObject;

class WriteDeviceSrcRequest extends FlexibleDataTransferObject
{
    public int $Node;
    public int $Ucsn;
    public int $Time;
    public int $install;

}
