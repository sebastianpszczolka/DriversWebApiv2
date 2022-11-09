<?php

declare(strict_types=1);

namespace App\Exceptions;

class NoMailerDriversException extends BaseException
{
    public function __construct()
    {
        parent::__construct('There is not drivers for Mailer', 'general.no_mailer_drivers');
    }
}