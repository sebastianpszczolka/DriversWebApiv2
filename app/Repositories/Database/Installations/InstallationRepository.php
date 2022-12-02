<?php
declare(strict_types=1);

namespace App\Repositories\Database\Installations;

use App\Entities\User;

class InstallationRepository
{
    public function getInstallationsForUser(User $user): array
    {
        return $user->installations()->get()->all();
    }

}
