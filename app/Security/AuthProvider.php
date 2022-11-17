<?php 

declare(strict_types=1);

namespace App\Security;

use App\Entities\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class AuthProvider
{
    public function authenticate(string $email, string $password): bool
    {
        return Auth::attempt(['email' => $email, 'password' => $password]);
    }

    public function createAccessToken(User $user): string
    {
        return $user->createToken(Config::get('app.name'))->accessToken;
    }

    public function destroyAccessToken(): bool
    {
        return $this->authenticated()->token()->forceDelete();
    }

    public function authenticated(): ?User
    {
        /** @var User|null $user */
        $user = Auth::user();

        return $user;
    }
}