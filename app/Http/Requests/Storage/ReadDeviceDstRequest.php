<?php

namespace App\Http\Requests\Storage;

use Spatie\DataTransferObject\FlexibleDataTransferObject;

class ReadDeviceDstRequest extends FlexibleDataTransferObject
{
    public int $Node;

    public int $install;

    public array $members;
}
