<?php

namespace App\Exceptions;

class NoPermissionsException extends BaseException
{
    public function __construct(string $message = 'No permissions for action')
    {
        parent::__construct($message, 'general.no_permissions');
    }
}
