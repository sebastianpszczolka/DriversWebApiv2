<?php

declare(strict_types=1);

namespace App\Services\User;

use App\Dto\Auth\CreateAccountDto;
use App\Dto\Emails\ActivationMailParamsDto;
use App\Exceptions\BaseException;
use App\Http\Requests\Auth\CreateAccountRequestData;
use App\Libraries\StringGenerator;
use App\Repositories\Database\UserRepository;
use App\Services\Mailing\MailerService;
use Throwable;

class AccountService
{
    private UserRepository $userRepository;
    private MailerService $mailerService;
    private StringGenerator $stringGenerator;

    public function __construct(
        UserRepository  $userRepository,
        MailerService   $mailerService,
        StringGenerator $stringGenerator
    )
    {
        $this->userRepository = $userRepository;
        $this->mailerService = $mailerService;
        $this->stringGenerator = $stringGenerator;
    }

    public function register(CreateAccountRequestData $createAccountDto): void
    {
        try {
            $activationCode = $this->stringGenerator->getRandomString(50);
            $user = $this->userRepository->createAccount(new CreateAccountDto($createAccountDto->toArray()), $activationCode);

            $this->mailerService->sendActivationEmail(new ActivationMailParamsDto([
                'email' => $user->getEmail(),
                'userId' => $user->getId(),
                'activationCode' => $user->getActivationCode(),
            ]));

        } catch (Throwable $e) {
            throw new BaseException($e->getMessage());
        }
    }
//
//    public function resentEmail(RequestActivationEmailResentRequestData $payload): void
//    {
//        $user = $this->userRepository->getByEmail($payload->email);
//
//        if (is_null($user)) {
//            return;
//        }
//
//        if ($user->isActivated() || is_null($user->getActivationCode())) {
//            throw new BaseException(sprintf('Activated user: %s', $payload->email), 'accounts.no_account_or_activated');
//        }
//
//        $this->mailerService->sendActivationEmail(new ActivationMailParamsDto([
//            'lang' => $user->getLang(),
//            'email' => $user->getEmail(),
//            'userId' => $user->getId(),
//            'activationCode' => $user->getActivationCode(),
//        ]));
//    }
//
//    public function activateAccount(int $userId, string $activationCode): void
//    {
//        $user = $this->userRepository->getById($userId);
//
//        if (is_null($user)) {
//            throw new BaseException('Cannot active user', 'accounts.invalid_activation_code');
//        }
//
//        if ($user->isActivated()) {
//            throw new BaseException('User already activated', 'accounts.user_already_activated');
//        }
//
//        if ($user->getActivationCode() !== $activationCode) {
//            throw new BaseException('Cannot active user', 'accounts.invalid_activation_code');
//        }
//
//        $this->userRepository->activateAccount($user);
//    }
//
//    public function resetPasswordStepOne(RequestResetPasswordRequestData $passwordRequestDto): void
//    {
//        $user = $this->userRepository->getByEmail($passwordRequestDto->email);
//
//        if (is_null($user)) {
//            throw new UserNotFoundException();
//        }
//
//        $resetPasswordCode = $this->stringGenerator->getRandomString(50);
//        $this->userRepository->setResetPasswordCode($user, $resetPasswordCode);
//
//        $this->mailerService->sendResetPasswordEmail(new ResetPasswordMailParamsDto([
//            'lang' => $user->getLang(),
//            'email' => $user->getEmail(),
//            'userId' => $user->getId(),
//            'passwordResetCode' => $resetPasswordCode,
//        ]));
//    }
//
//    public function resetPasswordStepTwo(int $userId, string $resetPasswordCode, ResetPasswordConfirmRequestData $params): void
//    {
//        $user = $this->userService->getById($userId);
//
//        if ($user->getResetPasswordCode() !== $resetPasswordCode) {
//            throw new BaseException('Invalid reset code ','accounts.reset_code_invalid');
//        }
//
//        $this->userRepository->changeUserPassword($user, $params->newPassword);
//    }
}
