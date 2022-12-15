<?php

namespace App\Libraries;

use Archive7z\Archive7z;

class CustomArchive7z extends Archive7z
{
    protected $timeout = 120;
    protected $compressionLevel = 6;
    protected $overwriteMode = self::OVERWRITE_MODE_S;
}
