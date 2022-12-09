<?php
declare(strict_types=1);

namespace App\Actions\Exchange;

use App\Actions\BaseAction;
use App\Exceptions\ValidationException;
use App\Services\Exchange\Exchange;
use App\Validators\Exchange\ExchangeValidator;
use Illuminate\Support\Facades\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ExchangeAction extends BaseAction
{
    private Exchange $exchange;


    public function __construct(Exchange $exchange)
    {
        $this->exchange = $exchange;
    }

    /**
     * @throws ValidationException
     * @throws \Exception
     */
    public function __invoke(): JsonResponse
    {
        $data = Request::input();

        (new ExchangeValidator($data))->validate();

        $result = $this->exchange->exchangeParameters($data);

        return $this->jsonResponseRaw($result, Response::HTTP_OK);

    }

}
