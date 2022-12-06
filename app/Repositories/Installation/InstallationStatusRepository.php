<?php
declare(strict_types=1);

namespace App\Repositories\Installation;

interface InstallationStatusRepository
{
    public function getSchemaNoByInstallationBarcode(int $instBarcode): ?int;
}
