<?php

declare(strict_types=1);

namespace App\Actions\Auth\ResetPassword;

use App\Actions\BaseAction;
use App\Http\Requests\Auth\RequestResetPasswordRequestData;
use App\Services\User\AccountService;
use App\Validators\Common\EmailValidator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Request;

class StartResetProcedureAction extends BaseAction
{
    private AccountService $accountService;

    public function __construct(AccountService $accountService)
    {
        $this->accountService = $accountService;
    }

    public function __invoke(): JsonResponse
    {
        $data = Request::input();
        (new EmailValidator($data))->validate();
        $params = new RequestResetPasswordRequestData($data);

        $this->accountService->resetPasswordStepOne($params);

        return $this->jsonResponse(null, static::ACTION_SAVED, static::STATUS_OK, trans('messages.password_reset_email_sent'));
    }
}
