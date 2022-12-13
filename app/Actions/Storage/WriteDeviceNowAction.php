<?php

namespace App\Actions\Storage;

use App\Actions\BaseAction;
use App\Dto\Storage\WriteDeviceNowDto;
use App\Http\Requests\Storage\WriteDeviceNowRequest;
use App\Services\Storage\StorageService;
use App\Validators\Storage\WriteDeviceNowActionValidator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Request;
use Symfony\Component\HttpFoundation\Response;

class WriteDeviceNowAction extends BaseAction
{
    private StorageService $storageService;

    public function __construct(StorageService $storageService)
    {
        $this->storageService = $storageService;
    }

    public function __invoke(): JsonResponse
    {
        $data = Request::input();

        (new WriteDeviceNowActionValidator($data))->validate();

        $writeDeviceData = new WriteDeviceNowRequest($data);
        $result = $this->storageService->writeDevNow($writeDeviceData);

        return $this->jsonResponseRaw($result->toArray(), Response::HTTP_OK);
    }

}
