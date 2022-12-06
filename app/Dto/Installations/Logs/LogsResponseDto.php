<?php
declare(strict_types=1);

namespace App\Dto\Installations\Logs;

use Spatie\DataTransferObject\DataTransferObject;

class LogsResponseDto extends DataTransferObject
{
    public string $data;
    public array $headers;

    public function jsonSerialize(): array
    {
        return [
            'headers' => $this->headers,
            'data' => $this->data,
        ];
    }

}

