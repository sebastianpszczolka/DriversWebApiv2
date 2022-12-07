<?php
declare(strict_types=1);

namespace App\Actions\Installations\Resources;

use App\Actions\BaseAction;
use App\Exceptions\BaseException;
use App\Exceptions\InstallationNotAssignedException;
use App\Exceptions\InstallationNotFoundException;
use App\Exceptions\ValidationException;
use App\Http\Requests\Installations\Resources\ReadResourceRequest;
use App\Security\AuthProvider;
use App\Services\Installations\InstallationService;
use App\Services\Installations\Resources\ResourceService;
use App\Validators\Installations\Resources\ReadResourceValidator;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;


class ReadResourceAction extends BaseAction
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
     * @return BinaryFileResponse
     * @throws InstallationNotFoundException
     * @throws ValidationException
     * @throws InstallationNotAssignedException
     * @throws BaseException
     */
    public function __invoke(int $installationId): BinaryFileResponse
    {
        $data = Request::input();
        (new ReadResourceValidator($data))->validate();

        $user = $this->authProvider->authenticated();
        $installation = $this->installationService->getById($installationId);
        $readResourceRequest = new ReadResourceRequest($data);
        $result = $this->resourceService->getResourceFilePath($user, $installation, $readResourceRequest);

        return Response::download($result);
    }

}
