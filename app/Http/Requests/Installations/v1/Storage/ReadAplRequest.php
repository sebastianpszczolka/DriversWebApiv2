<?php

namespace App\Http\Requests\Installations\v1\Storage;

use Spatie\DataTransferObject\FlexibleDataTransferObject;

class ReadAplRequest extends FlexibleDataTransferObject
{
    public array $members;
    public array $variables;
}
