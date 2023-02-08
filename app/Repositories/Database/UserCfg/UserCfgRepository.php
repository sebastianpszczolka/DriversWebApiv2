<?php

namespace App\Repositories\Database\UserCfg;

use App\Entities\User;
use App\Entities\UserCfg;

class UserCfgRepository
{
    /**
     * @param User $user
     * @return UserCfg|null
     */
    public function getConfigurationByUser(User $user): ?UserCfg
    {
        return $user->configuration()->get()->first();
    }

    /**
     * @param User $user
     * @param array $data
     * @return UserCfg
     */
    public function setConfigurationByUser(User $user, array $data): UserCfg
    {

        /** @var UserCfg $userCfg */
        $userCfg = $user->configuration()->updateOrCreate(
            [],
            [
                UserCfg::COLUMN_CFG => json_encode($data)
            ]
        );
        return $userCfg;
    }


}
