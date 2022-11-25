<?php

namespace App\Dto\Storage;

use Spatie\DataTransferObject\DataTransferObject;

class ReadMsgBoxSrcDto extends DataTransferObject
{
    public int $Time;
    public int $install;

}
