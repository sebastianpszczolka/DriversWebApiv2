<?php

namespace App\Http\Requests\Storage;

use Spatie\DataTransferObject\FlexibleDataTransferObject;

class ReadMsgBoxRequest extends FlexibleDataTransferObject
{
    public ReadMsgBoxSrcRequest $Src;
    public ReadMsgBoxDstRequest $Dst;

    public array $InpBox;

}
