<?php
declare(strict_types=1);

namespace App\Dto\Storage;

use Spatie\DataTransferObject\DataTransferObject;

class ReadDeviceSrcDto extends DataTransferObject
{
    public int $Time;
    public int $install;

}
