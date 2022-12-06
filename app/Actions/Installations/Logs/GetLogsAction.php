<?php
declare(strict_types=1);

namespace App\Actions\Installations\Logs;

use App\Actions\BaseAction;
use App\Exceptions\InstallationNotAssignedException;
use App\Exceptions\InstallationNotFoundException;
use App\Exceptions\ValidationException;
use App\Http\Requests\Installations\Logs\LogsParamsRequestData;
use App\Security\AuthProvider;
use App\Services\Installations\InstallationService;
use App\Services\Installations\LogsService;
use App\Validators\Installations\Logs\LogsParamsValidator;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\Response as ResponseCode;


class GetLogsAction extends BaseAction
{
    protected LogsService $logsService;
    protected InstallationService $installationService;
    protected AuthProvider $authProvider;

    public function __construct(LogsService $logsService, InstallationService $installationService, AuthProvider $authProvider)
    {
        $this->logsService = $logsService;
        $this->installationService = $installationService;
        $this->authProvider = $authProvider;
    }

    /**
     * @param int $installationId
     * @return HttpResponse
     * @throws InstallationNotFoundException
     * @throws ValidationException
     * @throws InstallationNotAssignedException
     */
    public function __invoke(int $installationId): HttpResponse
    {
        $data = Request::input();
        (new LogsParamsValidator($data))->validate();

        $user = $this->authProvider->authenticated();
        $installation = $this->installationService->getById($installationId);
        $logsParamsRequestData = new LogsParamsRequestData($data);

        $result = $this->logsService->getLogs($user, $installation, $logsParamsRequestData);

        return Response::make($result->data, ResponseCode::HTTP_OK, $result->headers);
    }
}
