<?php
declare(strict_types=1);

namespace App\Dto\Storage;

use Spatie\DataTransferObject\DataTransferObject;

class ReadMsgBoxDto extends DataTransferObject
{
    public ReadMsgBoxSrcDto $Src;
    public ReadMsgBoxDstDto $Dst;

    public array $InpBox;

}
