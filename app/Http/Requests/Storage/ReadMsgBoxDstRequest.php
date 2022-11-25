<?php

namespace App\Http\Requests\Storage;

use Spatie\DataTransferObject\FlexibleDataTransferObject;

class ReadMsgBoxDstRequest extends FlexibleDataTransferObject
{
    public int $Node;

    public int $install;
}
