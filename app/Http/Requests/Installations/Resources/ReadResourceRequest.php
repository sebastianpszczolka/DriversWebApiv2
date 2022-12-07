<?php
declare(strict_types=1);

namespace App\Http\Requests\Installations\Resources;

use Spatie\DataTransferObject\FlexibleDataTransferObject;

class ReadResourceRequest extends FlexibleDataTransferObject
{
    public ?string $filePath;
    public ?string $folderPath;
}
