<?php
declare(strict_types=1);

namespace App\Actions\Installations\Resources;

use App\Actions\BaseAction;
use App\Exceptions\BaseException;
use App\Exceptions\InstallationNotAssignedException;
use App\Exceptions\InstallationNotFoundException;
use App\Exceptions\ValidationException;
use App\Http\Requests\Installations\Resources\ListResourceRequest;
use App\Security\AuthProvider;
use App\Services\Installations\InstallationService;
use App\Services\Installations\Resources\ResourceService;
use App\Validators\Installations\Resources\ListResourceValidator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Request;
use Symfony\Component\HttpFoundation\Response as ResponseCode;

class ListResourceAction extends BaseAction
{

    protected ResourceService $resourceService;
    protected InstallationService $installationService;
    protected AuthProvider $authProvider;

    public function __construct(ResourceService $resourceService, InstallationService $installationService, AuthProvider $authProvider)
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
     * @throws ValidationException
     */
    public function __invoke(int $installationId): JsonResponse
    {
        $data = Request::input();
        (new ListResourceValidator($data))->validate();

        $user = $this->authProvider->authenticated();
        $installation = $this->installationService->getById($installationId);
        $listResourceRequest = new ListResourceRequest($data);
        $result = $this->resourceService->listResourceFolderPath($user, $installation, $listResourceRequest);

        return $this->jsonResponseRaw($result, ResponseCode::HTTP_OK);
    }

}
