<?php

declare(strict_types=1);

namespace App\Actions\Auth\Registration;

use App\Actions\BaseAction;
use App\Http\Requests\Auth\RequestActivationEmailResentRequestData;
use App\Services\User\AccountService;
use App\Validators\Auth\Registration\ResentEmailDataValidator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Request;
use function trans;

class RequestActivationEmailResentAction extends BaseAction
{
    private AccountService $accountService;

    public function __construct(AccountService $accountService)
    {
        $this->accountService = $accountService;
    }

    public function __invoke(): JsonResponse
    {
        $data = Request::input();
        (new ResentEmailDataValidator($data))->validate();
        $payload = new RequestActivationEmailResentRequestData($data);
        $this->accountService->resentEmail($payload);

        return $this->jsonResponse(null, static::ACTION_SAVED, static::STATUS_OK, trans('messages.activation_email_has_been_send'));
    }
}