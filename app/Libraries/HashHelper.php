<?php

declare(strict_types=1);

namespace App\Libraries;

class HashHelper
{
    public function hashPassword(string $password): string
    {
        return bcrypt($password);
    }
}
