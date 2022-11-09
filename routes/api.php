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

Route::prefix('registration')->group(function () {
    Route::post('register', Actions\Auth\Registration\RegisteringAction::class);
//    Route::post('resend', Actions\Auth\Registration\RequestActivationEmailResentAction::class);
//    Route::post('activate/{userId}/{activationCode}', Actions\Auth\Registration\ActivateAccountAction::class);
//    Route::get('terms', Actions\Auth\Registration\GetTermsAction::class);
});

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/test', function (Request $request) {
        return 'testowa wartosc';
    });

});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
