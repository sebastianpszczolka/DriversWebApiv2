<?php
declare(strict_types=1);

namespace App\Actions\Installations\Resources;

use App\Actions\BaseAction;
use App\Exceptions\BaseException;
use App\Exceptions\InstallationNotAssignedException;
use App\Exceptions\InstallationNotFoundException;
use App\Security\AuthProvider;
use App\Services\Installations\InstallationService;
use App\Services\Installations\Resources\ResourceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Request;
use Symfony\Component\HttpFoundation\Response;

class WriteResourceAction extends BaseAction
{
    protected ResourceService $resourceService;
    protected InstallationService $installationService;
    protected AuthProvider $authProvider;

    public function __construct(ResourceService     $resourceService,
                                InstallationService $installationService,
                                AuthProvider        $authProvider)
    {
        $this->resourceService = $resourceService;
        $this->installationService = $installationService;
        $this->authProvider = $authProvider;
    }

    /**
     * @param int $installationId
     * @return JsonResponse
     * @throws BaseException
     * @throws InstallationNotAssignedException
     * @throws InstallationNotFoundException
     */
    public function __invoke(int $installationId): JsonResponse
    {
        $file = Request::file('file');
        $folderPath = Request::input('folderPath');

        if (is_null($file)) {
            throw new BaseException('File is required');
        }

        if (is_null($folderPath)) {
            throw new BaseException('folderPath is required');
        }

        $user = $this->authProvider->authenticated();
        $installation = $this->installationService->getById($installationId);
        $result = $this->resourceService->writeResourceFile($user, $installation, $folderPath, $file);
        return $this->jsonResponseRaw($result, Response::HTTP_OK);
    }
}
