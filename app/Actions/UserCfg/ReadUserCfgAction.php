<?php

namespace App\Actions\UserCfg;

use App\Actions\BaseAction;
use App\Security\AuthProvider;
use App\Services\User\UserCfgService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ReadUserCfgAction extends BaseAction
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
        $user = $this->authProvider->authenticated();

        $result = $this->userCfgService->getConfigurationByUser($user);
        if (is_null($result))
            return $this->jsonResponseRaw([], Response::HTTP_OK);

        return $this->jsonResponseRaw($result->jsonSerialize(), Response::HTTP_OK);
    }

}
