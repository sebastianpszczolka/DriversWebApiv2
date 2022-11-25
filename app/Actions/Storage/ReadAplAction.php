<?php

namespace App\Actions\Storage;

use App\Actions\BaseAction;
use App\Services\Storage\StorageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Request;
use Symfony\Component\HttpFoundation\Response;

class ReadAplAction extends BaseAction
{
    private StorageService $storageService;

    public function __construct(StorageService $storeService)
    {
        $this->storageService = $storeService;
    }

    public function __invoke(): JsonResponse
    {
        $data = Request::input();

        $result = $this->storageService->readApl($data);

        return $this->jsonResponseRaw($result, Response::HTTP_OK);
    }
}
