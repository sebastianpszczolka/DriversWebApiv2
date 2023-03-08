<?php

namespace App\Http\Requests\Installations\Resources;

use Spatie\DataTransferObject\FlexibleDataTransferObject;

class ListResourceRequest extends FlexibleDataTransferObject
{
    public string $folderPath;
}
