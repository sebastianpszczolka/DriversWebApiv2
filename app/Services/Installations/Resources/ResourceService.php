<?php
declare(strict_types=1);

namespace App\Services\Installations\Resources;

use App\Entities\Installation;
use App\Entities\User;
use App\Exceptions\BaseException;
use App\Exceptions\InstallationNotAssignedException;
use App\Http\Requests\Installations\Resources\ReadResourceRequest;
use App\Libraries\Paths;
use App\Repositories\Installation\InstallationStatusRepository;

class ResourceService
{
    private const ROOT_DIR_NAME = 'VISU';
    private Paths $paths;
    private InstallationStatusRepository $installationStatusRepository;

    public function __construct(Paths $paths, InstallationStatusRepository $installationStatusRepository)
    {
        $this->paths = $paths;
        $this->installationStatusRepository = $installationStatusRepository;
    }


    /**
     * @param User $user
     * @param Installation $installation
     * @param ReadResourceRequest $params
     * @return string
     * @throws InstallationNotAssignedException
     * @throws BaseException
     */
    public function getResourceFilePath(User $user, Installation $installation, ReadResourceRequest $params): string
    {
        if ($installation->isAssignedToUser($user) === false) {
            throw new InstallationNotAssignedException();
        }

        $instBarcode = (string)$installation->getInstallationBarcode();
        $schId = $this->installationStatusRepository->getSchemaNoByInstallationBarcode($instBarcode);

        $instRootPath = $this->paths->createPath($this->paths->getInstBasePath($instBarcode), static::ROOT_DIR_NAME);
        $schRootPath = $this->paths->createPath($this->paths->getSchemaPath(), $schId, static::ROOT_DIR_NAME);

        if ($params->filePath !== null) {
            $filePathInst = $this->paths->createPath($instRootPath, $params->filePath);
            $filePathSch = $this->paths->createPath($schRootPath, $params->filePath);

            if (file_exists($filePathInst)) {
                return $filePathInst;
            } else if (file_exists($filePathSch)) {
                return $filePathSch;
            }
            throw new BaseException(sprintf('File do not exists. File Inst : %s. File Sch : %s.', $filePathInst, $filePathSch));

        } else if ($params->folderPath !== null) {
            $folderPathInst = $this->paths->createPath($instRootPath, $params->folderPath);
            if (file_exists($folderPathInst) && is_dir($folderPathInst)) {

                foreach (scandir($folderPathInst) as $item) {
                    if (in_array($item, ['.', '..'])) {
                        continue;
                    }

                    if (is_dir($item)) continue;
                    return $folderPathInst . DIRECTORY_SEPARATOR . $item;
                }
            }

            throw new BaseException(sprintf('Directory do not exists. Directory: %s.', $folderPathInst));


        } else {
            throw new BaseException(sprintf('File do not exists. File path: %s. Directory path: %s.', $params->filePath, $params->folderPath));
        }
    }
}
