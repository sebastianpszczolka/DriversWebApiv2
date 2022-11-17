<?php

declare(strict_types=1);

namespace App\Actions\Auth\Registration;

use App\Actions\BaseAction;
use Illuminate\Http\JsonResponse;
use function app;
use function view;

class GetTermsAction extends BaseAction
{
    public function __invoke(): JsonResponse
    {
        $lang = app()->getLocale();

        $view = view()
            ->make($lang . '.terms')
            ->render();

        return $this->jsonResponse([$view]);
    }
}
