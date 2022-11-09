<?php
declare(strict_types=1);

namespace App\Exceptions;

use App\Libraries\ValidationErrorsParser;
use Exception;
use Illuminate\Support\MessageBag;

class ValidationException extends Exception
{
    protected array $errorsBag;

    public function __construct(MessageBag $errors)
    {
        parent::__construct('Validation exception has occurred', 0, null);

        $this->errorsBag = (new ValidationErrorsParser())->parse($errors);
    }

    public function getValidationErrors(): array
    {
        return $this->errorsBag;
    }

}
