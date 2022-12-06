<?php
declare(strict_types=1);

namespace App\Repositories\Database\Installations;

use App\Entities\Installation;
use App\Entities\User;

class InstallationRepository
{
    public function getInstallationsForUser(User $user): array
    {
        return $user->installations()->get()->all();
    }

    public function getById(int $installationId): ?Installation
    {
        /** @var Installation | null $installation */
        $installation = Installation::query()->find($installationId);

        return $installation;
    }

}
