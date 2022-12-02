<?php
declare(strict_types=1);

namespace App\Actions\Installations;

use App\Actions\BaseAction;
use App\Security\AuthProvider;
use App\Services\Installations\InstallationService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class GetInstallationsAction extends BaseAction
{
    private InstallationService $installationService;
    private AuthProvider $authProvider;

    public function __construct(InstallationService $installationService, AuthProvider $authProvider)
    {
        $this->installationService = $installationService;
        $this->authProvider = $authProvider;
    }

    public function __invoke(): JsonResponse
    {
        $user = $this->authProvider->authenticated();

        $result = $this->installationService->getInstallationsForUser($user);
        return $this->jsonResponseRaw($result, Response::HTTP_OK);
    }

}
