<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;

class BaseException extends Exception
{
    protected string $errorKey;

    public function __construct(string $message = 'An unexpected error has occurred', string $errorMessageKey = 'general.general_error')
    {
        parent::__construct($message, 0, null);

        $this->errorKey = $errorMessageKey;
    }

    public function getErrorKey(): string
    {
        return $this->errorKey;
    }
}