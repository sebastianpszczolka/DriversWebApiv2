<?php

namespace App\Actions\UserCfg;

use App\Actions\BaseAction;
use App\Security\AuthProvider;
use App\Services\User\UserCfgService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Request;
use Symfony\Component\HttpFoundation\Response;

class WriteUserCfgAction extends BaseAction
{
    private AuthProvider $authProvider;
    private UserCfgService $userCfgService;

    public function __construct(AuthProvider $authProvider, UserCfgService $userCfgService)
    {
        $this->authProvider = $authProvider;
        $this->userCfgService = $userCfgService;
    }

    public function __invoke(): JsonResponse
    {
        $data = Request::input();
        $user = $this->authProvider->authenticated();

        $result = $this->userCfgService->setConfigurationByUser($user, $data);

        return $this->jsonResponseRaw($result->jsonSerialize(), Response::HTTP_OK);
    }

}
