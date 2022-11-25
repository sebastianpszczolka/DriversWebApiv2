<?php
declare(strict_types=1);

namespace App\Dto\Storage;

use Spatie\DataTransferObject\DataTransferObject;

class ReadDeviceDstDto extends DataTransferObject
{
    public int $Node;
    public int $install;

}
