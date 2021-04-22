<?php

namespace App\Services;

use GuzzleHttp\Client as HttpClient;
use Psr\Http\Message\ResponseInterface;
use Illuminate\Support\Facades\Log;

class SmsService
{

    public static function send(string $phone, string $text, array $params = [])
    {
        if (!config('sms.enabled')) {
            Log::info("Prevented sms to {$phone}. Text: ${text}");
            return;
        }

        $httpClient = new httpClient();

        $promise = $httpClient->requestAsync('GET', config('sms.base_url'), [
            'query' => array_merge($params, [
                'login'     => config('sms.login'),
                'phones'    => $phone,
                'mes'       => $text,
                'from'      => config('sms.from'),
                'psw'       => config('sms.psw'),
                'time'      => config('sms.time'),
            ]),
        ]);

        $promise->then(
            function (ResponseInterface $res) use ($phone) {
                Log::info("Sms sent to {$phone} with status: {$res->getStatusCode()}");
            },
            function (RequestException $e) use ($phone) {
                Log::error("Sms sending error! Phone: {$phone} {$e->getMessage()}");
            }
        );

        $promise->wait();
    }
}
