<?php

declare(strict_types=1);

namespace App\Services\User;

use App\Dto\Auth\AuthenticatedUserDto;
use App\Exceptions\BaseException;
use App\Exceptions\UserNotFoundException;
use App\Exceptions\ValidationException;
use App\Http\Requests\Auth\LoginRequestData;
use App\Security\AuthProvider;
use Illuminate\Support\MessageBag;
use Throwable;

class AuthService
{
    private AuthProvider $authProvider;

    public function __construct(
        AuthProvider $authProvider
    )
    {
        $this->authProvider = $authProvider;
    }

    public function login(LoginRequestData $credentials): AuthenticatedUserDto
    {
        if (!$this->authProvider->authenticate($credentials->email, $credentials->password)) {
            $errors = new MessageBag([
                'email' => trans('validation.email_or_password_incorrect'),
            ]);

            throw new ValidationException($errors);
        }

        $user = $this->authProvider->authenticated();

        if (!$user->isActivated()) {
            throw new BaseException('User is not activated', 'accounts.your_account_is_not_activated');
        }

        // Create token for user which will be used to authorization
        $token = $this->authProvider->createAccessToken($user);

        return new AuthenticatedUserDto(['user' => $user->jsonSerialize(), 'token' => $token]);
    }

    public function logout(): void
    {
        try {
            $user = $this->authProvider->authenticated();

            if (is_null($user)) {
                throw new UserNotFoundException();
            }

            $this->authProvider->destroyAccessToken();

        } catch (UserNotFoundException $e) {
            throw $e;
        } catch (Throwable $e) {
            throw new BaseException('Exception occurred while logging out');
        }
    }
}
