<?php

namespace App\Http\Requests\Installations\v1\Storage;

use Spatie\DataTransferObject\FlexibleDataTransferObject;

class WriteAplRequest  extends FlexibleDataTransferObject
{
    public int $expired;
    public array $variables;
}


