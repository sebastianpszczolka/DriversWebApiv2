<?php

declare(strict_types=1);

namespace App\Actions\Auth\Registration;

use App\Actions\BaseAction;
use App\Services\User\AccountService;
use Illuminate\Http\JsonResponse;
use function trans;

class ActivateAccountAction extends BaseAction
{
    private AccountService $accountService;

    public function __construct(AccountService $accountService)
    {
        $this->accountService = $accountService;
    }

    public function __invoke(int $userId, string $activationCode): JsonResponse
    {
        $this->accountService->activateAccount($userId, $activationCode);

        return $this->jsonResponse(null, static::ACTION_SAVED, static::STATUS_OK, trans('messages.user_activated'));
    }
}
