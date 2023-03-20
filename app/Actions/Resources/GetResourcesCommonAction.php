<?php

namespace App\Actions\Resources;

use App\Actions\BaseAction;
use App\Exceptions\BaseException;
use App\Services\Installations\Resources\ResourceService;

class GetResourcesCommonAction extends BaseAction
{
    protected ResourceService $resourceService;

    public function __construct(ResourceService $resourceService)
    {
        $this->resourceService = $resourceService;
    }

    /**
     * @throws BaseException
     */
    public function __invoke()
    {
        return $this->resourceService->getCommonResourceFolderZipped();
    }
}

