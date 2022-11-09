<?php
declare(strict_types=1);

namespace App\Actions\Auth\Registration;

use App\Actions\BaseAction;
use App\Http\Requests\Auth\CreateAccountRequestData;
use App\Services\User\AccountService;
use App\Validators\Auth\Registration\RegisterAccountDataValidator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Request;

class RegisteringAction extends BaseAction
{
    private AccountService $accountService;

    public function __construct(AccountService $accountService)
    {
        $this->accountService = $accountService;
    }

    public function __invoke(): JsonResponse
    {
        $data = Request::input();

        (new RegisterAccountDataValidator($data))->validate();

        $createAccountRequestData = new CreateAccountRequestData($data);
        $this->accountService->register($createAccountRequestData);
        return $this->createdResponse(null, trans('messages.confirmation_email_sent'));
    }
}
