<?php
declare(strict_types=1);

namespace App\Repositories\Logs\Dto;

class LogsGenerateParamsDto
{
    protected string $mode;
    protected string $instBarcode;

    public function __construct(string $mode, string $instBarcode)
    {
        $this->mode = $mode;
        $this->instBarcode = $instBarcode;
    }

    public function getMode(): string
    {
        return $this->mode;
    }

    public function getInstBarcode(): string
    {
        return $this->instBarcode;
    }
}
