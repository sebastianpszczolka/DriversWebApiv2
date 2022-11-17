<?php

declare(strict_types=1);

namespace App\Exceptions;

class UserNotFoundException extends BaseException
{
    public function __construct(string $message = 'User does not exists')
    {
        parent::__construct($message, 'accounts.user_do_not_exists');
    }
}