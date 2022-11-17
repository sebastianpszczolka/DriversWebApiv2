<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\Actions\BaseAction;
use App\Http\Requests\Auth\LoginRequestData;
use App\Services\User\AuthService;
use App\Validators\Auth\CredentialsValidator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Request;

class LoginAction extends BaseAction
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function __invoke(): JsonResponse
    {
        $data = Request::input();

        (new CredentialsValidator($data))->validate();

        $credentials = new LoginRequestData($data);
        $result = $this->authService->login($credentials);

        return $this->jsonResponse($result->toArray());
    }
}
