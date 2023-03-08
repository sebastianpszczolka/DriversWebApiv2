<?php
declare(strict_types=1);

namespace App\Services\Installations\Resources;

use App\Entities\Installation;
use App\Entities\User;
use App\Exceptions\BaseException;
use App\Exceptions\InstallationNotAssignedException;
use App\Exceptions\InstallationNotFoundException;
use App\Http\Requests\Installations\Resources\ListResourceRequest;
use App\Http\Requests\Installations\Resources\ReadResourceRequest;
use App\Libraries\Paths;
use App\Repositories\Installation\InstallationStatusRepository;
use App\Utils\PathHelper;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

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
     * @param ListResourceRequest $params
     * @return array|string
     * @throws BaseException
     * @throws InstallationNotAssignedException
     * @throws InstallationNotFoundException
     */
    public function listResourceFolderPath(User $user, Installation $installation, ListResourceRequest $params): array
    {
        $result = [];
        if ($installation->isAssignedToUser($user) === false) {
            throw new InstallationNotAssignedException();
        }

        $instBarcode = (string)$installation->getInstallationBarcode();
        $instRootPath = PathHelper::combine($this->paths->getInstBasePath($instBarcode), static::ROOT_DIR_NAME);

        $folderPathInst = PathHelper::fixPathTraversal(PathHelper::combine($instRootPath, $params->folderPath));
        if (file_exists($folderPathInst) && is_dir($folderPathInst)) {

            foreach (scandir($folderPathInst) as $file) {
                if (in_array($file, ['.', '..'])) {
                    continue;
                }

                $file = $params->folderPath . DIRECTORY_SEPARATOR . $file;
                if (is_dir($file) || !Str::endsWith(strtolower($file), '.html')) continue;
                $result[] = $file;
            }
        } else {
            throw new BaseException("Directory do not exists. Directory:  {$folderPathInst}");
        }

        return $result;

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

        $instRootPath = PathHelper::combine($this->paths->getInstBasePath($instBarcode), static::ROOT_DIR_NAME);
        $schRootPath = PathHelper::combine($this->paths->getSchemaPath(), $schId, static::ROOT_DIR_NAME);

        if ($params->filePath !== null) {
            $filePathInst = PathHelper::fixPathTraversal(PathHelper::combine($instRootPath, $params->filePath));
            $filePathSch = PathHelper::fixPathTraversal(PathHelper::combine($schRootPath, $params->filePath));

            if (file_exists($filePathInst)) {
                return $filePathInst;
            } else if (file_exists($filePathSch)) {
                return $filePathSch;
            }
            throw new BaseException("File do not exists. File Inst : {$filePathInst}. File Sch : {$filePathSch}.");

        } else if ($params->folderPath !== null) {
            $folderPathInst = PathHelper::fixPathTraversal(PathHelper::combine($instRootPath, $params->folderPath));
            if (file_exists($folderPathInst) && is_dir($folderPathInst)) {

                foreach (scandir($folderPathInst) as $file) {
                    if (in_array($file, ['.', '..'])) {
                        continue;
                    }

                    $file = $folderPathInst . DIRECTORY_SEPARATOR . $file;
                    if (is_dir($file)) continue;
                    return $file;
                }
            }

            throw new BaseException("Directory do not exists. Directory:  {$folderPathInst}");

        } else {
            throw new BaseException("File do not exists. File path: {$params->filePath}. Directory path: {$params->folderPath}");
        }
    }

    /**
     * @param User $user
     * @param Installation $installation
     * @param string $folderPath
     * @param UploadedFile $file
     * @return array
     * @throws InstallationNotAssignedException
     * @throws BaseException
     */
    public function writeResourceFile(User $user, Installation $installation, string $folderPath, UploadedFile $file): array
    {
        if ($installation->isAssignedToUser($user) === false) {
            throw new InstallationNotAssignedException();
        }

        $instBarcode = (string)$installation->getInstallationBarcode();
        $instRootPath = PathHelper::combine($this->paths->getInstBasePath($instBarcode), static::ROOT_DIR_NAME);
        $folderPathDest = PathHelper::fixPathTraversal(PathHelper::combine($instRootPath, $folderPath));
        $file->move($folderPathDest, $file->getClientOriginalName());

        return [];
    }
}
