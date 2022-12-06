<?php
declare(strict_types=1);

namespace App\Repositories\Logs\Dto;

class LogsDataDto
{
    protected array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function getData(): array
    {
        return $this->data;
    }
}
