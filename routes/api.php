<?php

use App\Actions;
use App\Actions\Installations\Logs\GetLogsAction;
use App\Actions\Installations\Resources\ReadResourceAction;
use App\Actions\Installations\Resources\WriteResourceAction;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('login', Actions\Auth\LoginAction::class);

Route::prefix('registration')->group(function () {
    Route::post('register', Actions\Auth\Registration\RegisteringAction::class);
    Route::post('resend', Actions\Auth\Registration\RequestActivationEmailResentAction::class);
    Route::get('activate/{userId}/{activationCode}', Actions\Auth\Registration\ActivateAccountAction::class);
    Route::get('terms', Actions\Auth\Registration\GetTermsAction::class);
});

Route::prefix('reset-password')->group(function () {
    Route::post('', Actions\Auth\ResetPassword\StartResetProcedureAction::class);
    Route::get('{userId}/{resetCode}', Actions\Auth\ResetPassword\SetNewPasswordAfterResetViewAction::class);
    Route::post('{userId}/{resetCode}', Actions\Auth\ResetPassword\SetNewPasswordAfterResetAction::class);
});

Route::middleware('auth:api')->group(function () {
    Route::post('logout', Actions\Auth\LogoutAction::class);

    Route::prefix('storage')->group(function () {
        Route::post('write_apl', Actions\Storage\WriteAplAction::class);
        Route::post('read_apl', Actions\Storage\ReadAplAction::class);
    });

    Route::prefix('installations')->group(function () {
        Route::get('', Actions\Installations\GetInstallationsAction::class);

        Route::prefix('{installationID}')->group(function () {

            Route::post('logs', GetLogsAction::class);

            Route::prefix('resources')->group(function () {
                Route::post('read', ReadResourceAction::class);
                Route::post('write', WriteResourceAction::class);
            });
        });
    });
});

Route::prefix('storage')->group(function () {
    Route::post('read_msgbox', Actions\Storage\ReadMsgBoxAction::class);
    Route::post('write_device', Actions\Storage\WriteDeviceAction::class);
    Route::post('read_device', Actions\Storage\ReadDeviceAction::class);
    Route::post('write_device_data_to_file', Actions\Storage\WriteDeviceNowAction::class);
});

Route::post('exchange', Actions\Exchange\ExchangeAction::class);

Route::middleware(['auth:api', 'access.admin'])->group(function () {
    Route::get('admin', function () {
        return 'admin restricted area';
    });
});

