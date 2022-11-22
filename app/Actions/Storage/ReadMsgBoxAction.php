<?php

namespace App\Actions\Storage;

use App\Actions\BaseAction;
use App\Services\Storage\StorageService;
use App\Validators\Storage\ReadMsgBoxActionValidator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Request;


class ReadMsgBoxAction extends BaseAction
{
    private StorageService $storageService;

    public function __construct(StorageService $storeService)
    {
        $this->storageService = $storeService;
    }

    public function __invoke(): JsonResponse
    {
        $data = Request::input();

        (new ReadMsgBoxActionValidator($data))->validate();
        $result = $this->storageService->readMsgBox($data);

        return $this->jsonResponse($result);

    }
}
