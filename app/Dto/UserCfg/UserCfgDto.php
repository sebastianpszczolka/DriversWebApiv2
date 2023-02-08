<?php

namespace App\Dto\UserCfg;

use Spatie\DataTransferObject\FlexibleDataTransferObject;

class UserCfgDto extends FlexibleDataTransferObject
{
    public int $user_id;
    public string $cfg;
}
