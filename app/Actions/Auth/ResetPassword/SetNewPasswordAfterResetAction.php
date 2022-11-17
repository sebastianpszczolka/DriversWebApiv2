<?php

declare(strict_types=1);

namespace App\Actions\Auth\ResetPassword;

use App\Actions\BaseAction;
use App\Http\Requests\Auth\ResetPasswordConfirmRequestData;
use App\Services\User\AccountService;
use App\Validators\Auth\Registration\ResetPasswordConfirmDataValidator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Request;

class SetNewPasswordAfterResetAction extends BaseAction
{
    private AccountService $accountService;

    public function __construct(AccountService $accountService)
    {
        $this->accountService = $accountService;
    }

    public function __invoke(int $userId, string $resetCode): JsonResponse
    {
        $data = Request::input();
        (new ResetPasswordConfirmDataValidator($data))->validate();
        $params = new ResetPasswordConfirmRequestData($data);

        $this->accountService->resetPasswordStepTwo($userId, $resetCode, $params);

        return $this->jsonResponse(null, static::ACTION_SAVED, static::STATUS_OK, trans('messages.password_changed'));
    }
}