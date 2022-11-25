<?php
declare(strict_types=1);

namespace App\Http\Requests\Storage;

use Spatie\DataTransferObject\FlexibleDataTransferObject;

class ReadMsgBoxDstRequest extends FlexibleDataTransferObject
{
    public int $Node;

    public int $install;
}
