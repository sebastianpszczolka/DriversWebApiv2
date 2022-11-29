<?php

namespace App\Actions\Auth\ResetPassword;

use App\Actions\BaseAction;
use Illuminate\Support\Facades\Config;
use Illuminate\View\View;

class SetNewPasswordAfterResetViewAction extends BaseAction
{
    public function __invoke(): View
    {
        $request = request();
        $lang = app()->getLocale();
        $baseLink = Config::get('app.links.client_app');

        return view($lang . '.reset-password', ['request' => $request, 'baselink' => $baseLink]);
    }
}
