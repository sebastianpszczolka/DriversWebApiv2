<?php
declare(strict_types=1);
namespace App\Actions\Storage;

use App\Actions\BaseAction;
use App\Http\Requests\Storage\WriteDeviceRequest;
use App\Services\Storage\StorageService;
use App\Validators\Storage\WriteDeviceActionValidator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Request;
use Symfony\Component\HttpFoundation\Response;

class WriteDeviceAction extends BaseAction
{

    private StorageService $storageService;

    public function __construct(StorageService $storeService)
    {
        $this->storageService = $storeService;
    }

    public function __invoke(): JsonResponse
    {
        $data = Request::input();

        (new WriteDeviceActionValidator($data))->validate();

        $writeDeviceData = new WriteDeviceRequest($data);
        $result = $this->storageService->writeDev($writeDeviceData);

        return $this->jsonResponseRaw($result->toArray(), Response::HTTP_OK);
    }

}
