<?php

namespace App\Repositories\Installation;

use App\Exceptions\InstallationNotFoundException;
use App\Libraries\Paths;
use App\Repositories\DeviceStatus\Dto\FilesystemInfoDto;

class FilesInstallationStatusRepository implements InstallationStatusRepository
{

    private Paths $paths;

    public function __construct(Paths $paths)
    {
        $this->paths = $paths;
    }

    /**
     * @param int $instBarcode
     * @return int|null
     * @throws InstallationNotFoundException
     */
    public function getSchemaNoByInstallationBarcode(int $instBarcode): ?int//641 to trzeba zaimplementowac
    {
        $path = $this->paths->getInstBasePath($instBarcode);

        if (empty($path)) {
            throw new InstallationNotFoundException();
        }

        // Explode base path by / (slash) (on last place it should be schema number)
        $tmp = explode('/', $path);

        // Trim and delete all unexpected chars
        $nrSch = rtrim(trim(end($tmp)), ';');

        return is_numeric($nrSch) ? $nrSch : null;
    }
}
