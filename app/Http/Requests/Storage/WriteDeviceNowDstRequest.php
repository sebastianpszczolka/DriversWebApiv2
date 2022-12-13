<?php
declare(strict_types=1);

namespace App\Http\Requests\Storage;

use Spatie\DataTransferObject\FlexibleDataTransferObject;

class WriteDeviceNowDstRequest extends FlexibleDataTransferObject
{
    public int $Node;

    public int $install;

    public string $fileName;
    public string $coment;

}
