<?php

namespace App\Exceptions;

class InstallationNotAssignedException extends BaseException
{
    public function __construct(string $message = 'Installation does not assigned')
    {
        parent::__construct($message, 'installations.installation_do_not_assigned');
    }
}
