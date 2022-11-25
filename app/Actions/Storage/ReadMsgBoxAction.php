<?php
declare(strict_types=1);
namespace App\Actions\Storage;

use App\Actions\BaseAction;
use App\Http\Requests\Storage\ReadMsgBoxRequest;
use App\Services\Storage\StorageService;
use App\Validators\Storage\ReadMsgBoxActionValidator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Request;
use Symfony\Component\HttpFoundation\Response;


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

        $readMsgBoxData = new ReadMsgBoxRequest($data);
        $result = $this->storageService->readMsgBox($readMsgBoxData);

        return $this->jsonResponseRaw($result->toArray(), Response::HTTP_OK);
    }
}
