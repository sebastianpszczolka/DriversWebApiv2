<?php

namespace App\Services\User;

use App\Entities\User;
use App\Entities\UserCfg;
use App\Repositories\Database\UserCfg\UserCfgRepository;

class UserCfgService
{
    private UserCfgRepository $userCfgRepository;

    public function __construct(UserCfgRepository $userCfgRepository)
    {
        $this->userCfgRepository = $userCfgRepository;
    }

    /**
     * @param User $user
     * @return UserCfg|null
     */
    public function getConfigurationByUser(User $user): ?UserCfg
    {
        return $this->userCfgRepository->getConfigurationByUser($user);
    }

    public function setConfigurationByUser(User $user, array $data): UserCfg
    {
        return $this->userCfgRepository->setConfigurationByUser($user, $data);

    }
}
