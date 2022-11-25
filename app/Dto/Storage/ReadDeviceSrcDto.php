<?php

namespace App\Dto\Storage;

use Spatie\DataTransferObject\DataTransferObject;

class ReadDeviceSrcDto extends DataTransferObject
{
    public int $Time;
    public int $install;

}
