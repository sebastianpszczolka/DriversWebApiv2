<?php

namespace App\Dto\Storage;

use Spatie\DataTransferObject\DataTransferObject;

class WriteDeviceDstDto extends DataTransferObject
{
    public int $Node;
    public int $install;
}
