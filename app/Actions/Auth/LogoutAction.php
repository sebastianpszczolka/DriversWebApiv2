<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\Actions\BaseAction;
use App\Exceptions\BaseException;
use App\Services\User\AuthService;
use Illuminate\Http\JsonResponse;

class LogoutAction extends BaseAction
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * @throws BaseException
     */
    public function __invoke(): JsonResponse
    {
        $this->authService->logout();

        return $this->jsonResponse();
    }
}