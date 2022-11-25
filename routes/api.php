<?php

use App\Actions;
use Illuminate\Http\Request;
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
    Route::post('activate/{userId}/{activationCode}', Actions\Auth\Registration\ActivateAccountAction::class);
    Route::get('terms', Actions\Auth\Registration\GetTermsAction::class);
});

Route::prefix('reset-password')->group(function () {
    Route::post('', Actions\Auth\ResetPassword\StartResetProcedureAction::class);
    Route::post('{userId}/{resetCode}', Actions\Auth\ResetPassword\SetNewPasswordAfterResetAction::class);
});

Route::middleware('auth:api')->group(function () {
    Route::post('logout', Actions\Auth\LogoutAction::class);
});

Route::prefix('storage')->group(function () {
    Route::post('msgbox', Actions\Storage\ReadMsgBoxAction::class);
    Route::post('write_device', Actions\Storage\WriteDeviceAction::class);
    Route::post('read_device', Actions\Storage\ReadDeviceAction::class);
});
