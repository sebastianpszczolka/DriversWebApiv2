<?php
declare(strict_types=1);

namespace App\Services\Installations;

use App\Entities\User;
use App\Repositories\Database\Installations\InstallationRepository;

class InstallationService
{
    private InstallationRepository $installationRepository;

    public function __construct(InstallationRepository $installationRepository)
    {
        $this->installationRepository = $installationRepository;
    }

    public function getInstallationsForUser(User $user): array
    {
        return $this->installationRepository->getInstallationsForUser($user);
    }
}
