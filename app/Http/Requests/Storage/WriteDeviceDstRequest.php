<?php

namespace App\Http\Requests\Storage;

use Spatie\DataTransferObject\FlexibleDataTransferObject;

class WriteDeviceDstRequest extends FlexibleDataTransferObject
{
    public int $Node;

    public int $install;

    public string $mount;
}
