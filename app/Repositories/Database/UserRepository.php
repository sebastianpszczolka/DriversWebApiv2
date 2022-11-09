<?php

declare(strict_types=1);

namespace App\Repositories\Database;

use App\Dto\Auth\CreateAccountDto;
use App\Entities\User;
use App\Libraries\HashHelper;

class UserRepository
{
    private HashHelper $hashHelper;

    public function __construct(
        HashHelper $hashHelper
    )
    {
        $this->hashHelper = $hashHelper;
    }

    public function update(User $user): void
    {
        $user->save();
    }

    public function getByEmail(string $email): ?User
    {
        /** @var User | null $user */
        $user = User::query()
            ->where(User::COLUMN_EMAIL, '=', $email)
            ->first();

        return $user;
    }

    public function getById(int $id): ?User
    {
        /** @var User | null $user */
        $user = User::query()->find($id);

        return $user;
    }

    /**
     * @param int[] $usersIds
     * @return User[]
     */
    public function getByIds(array $usersIds): array
    {
        return User::query()
            ->whereIn(User::COLUMN_ID, $usersIds)
            ->get()
            ->all();
    }

    /**
     * @return User[]
     */
    public function getAll(): array
    {
        return User::all()->all();
    }

    public function createAccount(CreateAccountDto $accountDto, string $activationCode): User
    {
        $user = new User([
            User::COLUMN_EMAIL => $accountDto->email,
            User::COLUMN_PASSWORD => $this->hashHelper->hashPassword($accountDto->password),
            User::COLUMN_ACTIVATION_CODE => $activationCode
        ]);
        $user->save();

        return $user;
    }

    public function setActivationCode(User $user, string $activationCode): User
    {
        $user->update([
            User::COLUMN_ACTIVATION_CODE => $activationCode,
        ]);

        return $user;
    }

    public function setResetPasswordCode(User $user, string $resetPasswordCode): User
    {
        $user->update([
            User::COLUMN_RESET_PASSWORD_CODE => $resetPasswordCode,
        ]);

        return $user;
    }

    public function activateAccount(User $user): User
    {
        $user->update([
            User::COLUMN_ACTIVATED => true,
            User::COLUMN_ACTIVATED_AT => $user->freshTimestamp(),
            User::COLUMN_ACTIVATION_CODE => null,
        ]);

        return $user;
    }

    public function updateLastLogin(User $user): User
    {
        $user->updateLastLogin();
        $user->save();

        return $user;
    }

    public function changeUserPassword(User $user, string $newPassword): User
    {
        $user->update([
            User::COLUMN_PASSWORD => $this->hashHelper->hashPassword($newPassword),
            User::COLUMN_RESET_PASSWORD_CODE => null,
        ]);

        return $user;
    }

    public function delete(User $user): void
    {

    }
}
