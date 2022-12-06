<?php
declare(strict_types=1);

namespace App\Services\Installations;

use App\Entities\Installation;
use App\Entities\User;
use App\Exceptions\InstallationNotFoundException;
use App\Repositories\Database\Installations\InstallationRepository;

class InstallationService
{
    private InstallationRepository $installationRepository;

    /**
     * @param InstallationRepository $installationRepository
     */
    public function __construct(InstallationRepository $installationRepository)
    {
        $this->installationRepository = $installationRepository;
    }

    /**
     * @param User $user
     * @return array
     */
    public function getInstallationsForUser(User $user): array
    {
        return $this->installationRepository->getInstallationsForUser($user);
    }

    /**
     * @param int $installationId
     * @return Installation
     * @throws InstallationNotFoundException
     */
    public function getById(int $installationId): Installation
    {
        $installation = $this->installationRepository->getById($installationId);

        if (is_null($installation)) {
            throw new InstallationNotFoundException();
        }

        return $installation;
    }
}
