<?php

declare(strict_types=1);

namespace App\Services\Mailing;

use App\Dto\Emails\ActivationMailParamsDto;
use App\Dto\Emails\ResetPasswordMailParamsDto;
use App\Exceptions\NoMailerDriversException;
use App\Loggers\DefaultLogger;
use Exception;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;

class MailerService
{
    private DefaultLogger $logger;

    public function __construct(DefaultLogger $logger)
    {
        $this->logger = $logger;
    }

    public function sendActivationEmail(ActivationMailParamsDto $params): void
    {
        $sendInfo = [
            'emailAddress' => $params->email,
            'emailSubject' => trans('emails.register_email_title')
        ];

        // Get base link (URL) for environment
        // this is important because when app run on testing environment there base link for client can be different from production
        // so to be able to test also emails (register etc), there is passed base link for URL
        $baseLink = Config::get('app.links.client_app');
        $mailParams = ['id' => $params->userId, 'activationCode' => $params->activationCode, 'baseLink' => $baseLink];

        try {
            Mail::send(sprintf('%s.activate', 'en'), $mailParams, function (Message $message) use ($sendInfo) {
                $message->to($sendInfo['emailAddress'], '')->subject($sendInfo['emailSubject']);
            });
        } catch (Exception $e) {
            // If this block of code is caught exception then there is high probability that error was
            // cause due to lack of mailer driver so throw this kind of exception

            $this->logger->critical($e->getMessage(), array_merge($sendInfo, $mailParams));

            throw new NoMailerDriversException();
        }
    }


    public function sendResetPasswordEmail(ResetPasswordMailParamsDto $params): void
    {

        $emailAddress = $params->email;
        $emailSubject = trans('emails.password_reset_email');

        $baseLink = Config::get('app.links.client_app');
        $emailPayload = [
            'id' => $params->userId,
            'resetCode' => $params->passwordResetCode,
            'baseLink' => $baseLink,
        ];

        try {
            Mail::send(sprintf('%s.reset', 'en'), $emailPayload, function (Message $message) use ($emailAddress, $emailSubject) {
                $message->to($emailAddress, '')->subject($emailSubject);
            });
        } catch (Exception $e) {
            // If any error has occurred here then it high probability there is no mailer drivers, so throw that exception
            $this->logger->critical($e->getMessage(), array_merge($emailPayload, ['email' => $emailAddress]));

            throw new NoMailerDriversException();
        }
    }
}
