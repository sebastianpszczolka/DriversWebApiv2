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
use Illuminate\Http\UploadedFile;

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

        $instRootPath = $this->paths->joinPath($this->paths->getInstBasePath($instBarcode), static::ROOT_DIR_NAME);
        $schRootPath = $this->paths->joinPath($this->paths->getSchemaPath(), $schId, static::ROOT_DIR_NAME);

        if ($params->filePath !== null) {
            $filePathInst = $this->paths->joinPath($instRootPath, $params->filePath);
            $filePathSch = $this->paths->joinPath($schRootPath, $params->filePath);

            if (file_exists($filePathInst)) {
                return $filePathInst;
            } else if (file_exists($filePathSch)) {
                return $filePathSch;
            }
            throw new BaseException("File do not exists. File Inst : {$filePathInst}. File Sch : {$filePathSch}.");

        } else if ($params->folderPath !== null) {
            $folderPathInst = $this->paths->joinPath($instRootPath, $params->folderPath);
            if (file_exists($folderPathInst) && is_dir($folderPathInst)) {

                foreach (scandir($folderPathInst) as $item) {
                    if (in_array($item, ['.', '..'])) {
                        continue;
                    }

                    if (is_dir($item)) continue;//641 poprawka do zorbienia
                    return $folderPathInst . DIRECTORY_SEPARATOR . $item;
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
        $instRootPath = $this->paths->joinPath($this->paths->getInstBasePath($instBarcode), static::ROOT_DIR_NAME);
        $folderPathDest = $this->paths->joinPath($instRootPath, $folderPath);
        $file->move($folderPathDest, $file->getClientOriginalName());

        return [];
    }
}
