<?php
declare(strict_types=1);

namespace App\Actions\Storage;

use App\Actions\BaseAction;
use App\Http\Requests\Storage\ReadDeviceRequest;
use App\Services\Storage\StorageService;
use App\Validators\Storage\ReadDeviceActionValidator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Request;
use Symfony\Component\HttpFoundation\Response;

class ReadDeviceAction extends BaseAction
{

    private StorageService $storageService;

    public function __construct(StorageService $storeService)
    {
        $this->storageService = $storeService;
    }

    public function __invoke(): JsonResponse
    {
        $data = Request::input();

        (new ReadDeviceActionValidator($data))->validate();

        $readDeviceData = new ReadDeviceRequest($data);
        $result = $this->storageService->readDev($readDeviceData);

        return $this->jsonResponseRaw($result->toArray(), Response::HTTP_OK);
    }

}
