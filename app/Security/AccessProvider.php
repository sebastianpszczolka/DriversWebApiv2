<?php

namespace App\Security;

use App\Entities\Group;
use App\Entities\User;
use App\Enum\Permissions;
use App\Exceptions\BaseException;
use Throwable;

class AccessProvider
{
    private AuthProvider $authProvider;

    /**
     * @param AuthProvider $authProvider
     */
    public function __construct(AuthProvider $authProvider)
    {
        $this->authProvider = $authProvider;
    }

    private array $userPermissionsCache = [];

    /**
     * @param array $permissions
     * @return bool
     * @throws BaseException
     */
    public function hasAuthenticatedPermissions(array $permissions): bool
    {
        $user = $this->authProvider->authenticated();
        if (is_null($user)) {
            return false;
        }

        return $this->hasPermissions($user, $permissions);
    }

    /**
     * @param User $user
     * @param array $requiredPermissions
     * @return bool
     * @throws BaseException
     */
    public function hasPermissions(User $user, array $requiredPermissions): bool
    {
        if (!isset($this->userPermissionsCache[$user->getId()])) {
            $this->userPermissionsCache[$user->getId()] = $this->getPermissions($user);
        }

        $permissions = $this->userPermissionsCache[$user->getId()];

        foreach ($requiredPermissions as $key) {
            if (!array_key_exists($key, $permissions)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @return bool
     * @throws BaseException
     */
    public function isAdmin(): bool
    {
        return $this->hasAuthenticatedPermissions([Permissions::ADMIN]);
    }

    /**
     * @param User $user
     * @return array
     * @throws BaseException
     */
    public function getPermissions(User $user): array
    {
        try {
            $permissions = [];
            $groups = $user->groups()->get()->all();

            /** @var Group $group */
            foreach ($groups as $group) {
                $groupPermissions = $group->getPermissions();
                if (!empty($groupPermissions)) {
                    $permissions = array_merge($permissions, $group->getPermissions());
                }
            }

            return $permissions;
        } catch (Throwable $e) {
            throw new BaseException($e->getMessage());
        }
    }
}
