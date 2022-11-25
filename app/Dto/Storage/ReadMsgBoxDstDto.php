<?php

namespace App\Dto\Storage;

use Spatie\DataTransferObject\DataTransferObject;

class ReadMsgBoxDstDto extends DataTransferObject
{
    public int $Node;
    public int $install;

}
