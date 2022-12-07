<?php
declare(strict_types=1);

namespace App\Repositories\Installation;

use App\Exceptions\InstallationNotFoundException;
use App\Libraries\Paths;

class FilesInstallationStatusRepository implements InstallationStatusRepository
{

    private Paths $paths;

    public function __construct(Paths $paths)
    {
        $this->paths = $paths;
    }

    /**
     * @param string $instBarcode
     * @return string|null
     * @throws InstallationNotFoundException
     */
    public function getSchemaNoByInstallationBarcode(string $instBarcode): ?string
    {
        $path = $this->paths->getInstBasePath($instBarcode);

        if (empty($path)) {
            throw new InstallationNotFoundException();
        }

        // Explode base path by / (slash) (on last place it should be schema number)
        $tmp = explode('/', $path);

        // Trim and delete all unexpected chars
        return rtrim(trim(end($tmp)), ';');
    }
}
