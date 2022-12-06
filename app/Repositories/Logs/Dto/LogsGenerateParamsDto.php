<?php
declare(strict_types=1);

namespace App\Repositories\Logs\Dto;

class LogsGenerateParamsDto
{
    protected string $mode;
    protected int $instBarcode;
    protected array $commands;

    public function __construct(string $mode, int $instBarcode, array $commands)
    {
        $this->mode = $mode;
        $this->instBarcode = $instBarcode;
        $this->commands = $commands;
    }

    public function getMode(): string
    {
        return $this->mode;
    }

    public function getInstBarcode(): int
    {
        return $this->instBarcode;
    }


    public function getCommands(): array
    {
        return $this->commands;
    }

}
