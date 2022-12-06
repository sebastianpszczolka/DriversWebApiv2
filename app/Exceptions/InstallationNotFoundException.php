<?php

namespace App\Exceptions;

class InstallationNotFoundException extends BaseException
{
    public function __construct(string $message = 'Installation does not exists')
    {
        parent::__construct($message, 'installations.installation_do_not_exists');
    }
}
