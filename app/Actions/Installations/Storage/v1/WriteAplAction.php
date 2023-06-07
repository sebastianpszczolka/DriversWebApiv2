<?php

declare(strict_types=1);

namespace App\Actions\Installations\Storage\v1;

use App\Actions\BaseAction;
use App\Exceptions\InstallationNotAssignedException;
use App\Exceptions\InstallationNotFoundException;
use App\Exceptions\ValidationException;
use App\Http\Requests\Installations\v1\Storage\WriteAplRequest;
use App\Security\AuthProvider;
use App\Services\Installations\InstallationService;
use App\Services\Storage\v1\StorageService;
use App\Validators\Installations\Storage\v1\WriteAplActionValidator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Request;
use Symfony\Component\HttpFoundation\Response;

class WriteAplAction extends BaseAction
{
    protected StorageService $storageService;
    protected InstallationService $installationService;
    protected AuthProvider $authProvider;

    public function __construct(StorageService $storeService, InstallationService $installationService, AuthProvider $authProvider)
    {
        $this->storageService = $storeService;
        $this->installationService = $installationService;
        $this->authProvider = $authProvider;
    }

    /**
     * @throws ValidationException
     * @throws InstallationNotAssignedException
     * @throws InstallationNotFoundException
     */
    public function __invoke(int $installationId): JsonResponse
    {
        $data = Request::input();

        $user = $this->authProvider->authenticated();
        $installation = $this->installationService->getById($installationId);

        if ($installation->isAssignedToUser($user) === false) {
            throw new InstallationNotAssignedException();
        }

        (new WriteAplActionValidator($data))->validate();
        $writeAplData = new WriteAplRequest($data);

        $result = $this->storageService->writeApl($writeAplData, $installation->getInstallationBarcode());

        return $this->jsonResponseRaw($result, Response::HTTP_OK);
    }
}

