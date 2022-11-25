<?php

declare(strict_types=1);

namespace App\Actions;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\Response;

class BaseAction extends Controller
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;

    const STATUS_OK = 'OK';
    const STATUS_ERROR = 'ERROR';
    const ACTION_GET = 'get';
    const ACTION_CREATED = 'created';
    const ACTION_SAVED = 'saved';
    const ACTION_RESTORED = 'restored';
    const ACTION_UPDATED = 'updated';
    const ACTION_DELETED = 'deleted';
    const ACTION_MOVED = 'moved';

    public function jsonResponseRaw(array $data, int $code): JsonResponse
    {
        return response()->json($data, $code, [], JSON_UNESCAPED_UNICODE);
    }

    public function jsonResponse(?array $data = null, ?string $action = null, ?string $status = null, ?string $message = null): JsonResponse
    {
        $action = $action ?? static::ACTION_GET;
        $status = $status ?? static::STATUS_OK;

        return response()->json([
            'status' => $status,
            'data' => $data,
            'action' => $action,
            'message' => $message ?? null,
        ], $this->getCodeCode($status, $action), [], JSON_UNESCAPED_UNICODE);
    }

    public function createdResponse(?array $data = null, ?string $message = null): JsonResponse
    {
        return response()->json([
            'status' => self::STATUS_OK,
            'data' => $data,
            'action' => self::ACTION_CREATED,
            'message' => $message ?? null,
        ], $this->getCodeCode(self::STATUS_OK, self::ACTION_CREATED), [], JSON_UNESCAPED_UNICODE);
    }

    public function updatedResponse(?array $data = null, ?string $message = null): JsonResponse
    {
        return response()->json([
            'status' => self::STATUS_OK,
            'data' => $data,
            'action' => self::ACTION_UPDATED,
            'message' => $message ?? null,
        ], $this->getCodeCode(self::STATUS_OK, self::ACTION_UPDATED), [], JSON_UNESCAPED_UNICODE);
    }

    public function deletedResponse(?string $message = null): JsonResponse
    {
        return response()->json([
            'status' => self::STATUS_OK,
            'data' => [],
            'action' => self::ACTION_DELETED,
            'message' => $message ?? null,
        ], $this->getCodeCode(self::STATUS_OK, self::ACTION_DELETED), [], JSON_UNESCAPED_UNICODE);
    }

    private function getCodeCode(string $status, string $action): int
    {
        if ($status === static::STATUS_ERROR) {
            return Response::HTTP_BAD_REQUEST;
        }

        switch ($action) {
            case static::ACTION_GET:
                return Response::HTTP_OK;
            case static::ACTION_CREATED:
                return Response::HTTP_CREATED;
            case static::ACTION_MOVED:
            case static::ACTION_RESTORED:
            case static::ACTION_UPDATED:
            case static::ACTION_SAVED:
                return Response::HTTP_ACCEPTED;
            case static::ACTION_DELETED:
                return Response::HTTP_NO_CONTENT;
        }

        return Response::HTTP_OK;
    }
}
