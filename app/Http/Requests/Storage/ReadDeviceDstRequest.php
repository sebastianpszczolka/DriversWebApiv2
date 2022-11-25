<?php
declare(strict_types=1);

namespace App\Http\Requests\Storage;

use Spatie\DataTransferObject\FlexibleDataTransferObject;

class ReadDeviceDstRequest extends FlexibleDataTransferObject
{
    public int $Node;

    public int $install;

    public array $members;
}
