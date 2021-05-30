<?php

namespace App\Services\Payment\CloudPayment;
use Illuminate\Support\Facades\Http;
use App\Models\UserBankCard;

class CloudPaymentService
{

    protected $url = 'https://api.cloudpayments.ru/';

    protected $publicKey;

    protected $privateKey;

    protected $locale = 'en-US';

    protected $currency = 'KZT';


    public function __construct()
    {
        $this->publicKey = config('cloudpayment.public_key');
        $this->privateKey = config('cloudpayment.private_key');
    }

    protected function sendRequest($endpoint, array $params = [], array $headers = [])
    {
        $params['CultureName'] = $this->locale;
        $headers[] = 'Content-Type: application/json';

		return Http::withBasicAuth($this->publicKey, $this->privateKey)
			->withHeaders($headers)
			->post($this->url . $endpoint, $params)
			->json();
    }

    public function getLocale()
    {
        return $this->locale;
    }

    public function setLocale($locale)
    {
        $this->locale = $locale;
    }

    public function chargeCard($amount, $ipAddress, $cardHolderName, $cryptogram, $params = [], $requireConfirmation = false)
    {
        $endpoint = $requireConfirmation ? '/payments/cards/auth' : '/payments/cards/charge';
        $defaultParams = [
            'Amount' => $amount,
            'Currency' => $this->currency,
            'IpAddress' => $ipAddress,
            'Name' => $cardHolderName,
            'CardCryptogramPacket' => $cryptogram
        ];

        $response = $this->sendRequest($endpoint, array_merge($defaultParams, $params));

        if (isset($response['Success']) && $response['Success']) {
            return Model\Transaction::fromArray($response['Model']);
        }

        if (isset($response['Message']) && $response['Message']) {
            throw new Exception\RequestException($response);
        }

        if (isset($response['Model']) && isset($response['Model']['ReasonCode']) && $response['Model']['ReasonCode'] !== 0) {
           throw new Exception\PaymentException($response);
        }

        return Model\Required3DS::fromArray($response['Model']);
    }

    public function chargeToken(UserBankCard $card, $amount, $params = [], $requireConfirmation = false)
    {
        $endpoint = $requireConfirmation ? '/payments/tokens/auth' : '/payments/tokens/charge';
        $defaultParams = [
            'Amount' => $amount,
            'Currency' => $this->currency,
            'AccountId' => $card->user_id,
            'Token' => $card->token,
        ];

        $response = $this->sendRequest($endpoint, array_merge($defaultParams, $params));

        if (isset($response['Success']) && $response['Success']) {
            return Model\Transaction::fromArray($response['Model']);
        }

        if (isset($response['Message']) && $response['Message']) {
            throw new Exception\RequestException($response);
        }

        if (isset($response['Model']) && isset($response['Model']['ReasonCode']) && $response['Model']['ReasonCode'] !== 0) {
            throw new Exception\PaymentException($response);
        }

        return Model\Required3DS::fromArray($response['Model']);
    }

    public function confirm3DS($transactionId, $token)
    {
        $response = $this->sendRequest('/payments/cards/post3ds', [
            'TransactionId' => $transactionId,
            'PaRes' => $token
        ]);

        if (isset($response['Message']) && $response['Message']) {
            throw new Exception\RequestException($response);
        }

        if (isset($response['Model']) && isset($response['Model']['ReasonCode']) && $response['Model']['ReasonCode'] !== 0) {
            throw new Exception\PaymentException($response);
        }

        return Model\Transaction::fromArray($response['Model']);
    }

    public function confirmPayment($transactionId, $amount)
    {
        $reponse = $this->sendRequest('/payments/confirm', [
            'TransactionId' => $transactionId,
            'Amount' => $amount
        ]);

        if (isset($response['Success']) && !$response['Success']) {
            throw new Exception\RequestException($response);
        }
    }

    public function voidPayment($transactionId)
    {
        $response = $this->sendRequest('/payments/void', [
            'TransactionId' => $transactionId
        ]);

        if (isset($response['Success']) && !$response['Success']) {
            throw new Exception\RequestException($response);
        }
    }

    public function refundPayment($transactionId, $amount)
    {
        $response = $this->sendRequest('/payments/refund', [
            'TransactionId' => $transactionId,
            'Amount' => $amount
        ]);

        if (isset($response['Success']) && !$response['Success']) {
            throw new Exception\RequestException($response);
        }
    }

    public function findPayment($invoiceId)
    {
        $response = $this->sendRequest('/payments/find', [
            'InvoiceId' => $invoiceId
        ]);

        if (isset($response['Success']) && !$response['Success']) {
           throw new Exception\RequestException($response);
        }

        return Model\Transaction::fromArray($response['Model']);
    }

    public function listPayment($date = '', $timezone = '')
    {
        if ($date == '') {
            $date == date('Y-m-d'); //Today
        }

        $response = $this->sendRequest('/payments/list', [
            'Date' => $date,
            'TimeZone' => $timezone
        ]);

        if (isset($response['Success']) && !$response['Success']) {
            throw new Exception\RequestException($response);
        }

        return Model\Transaction::fromArray($response['Model']);
    }

    /**
     * @param $data
     * @param $idempotent_id
     * @throws RequestException
     */
    public function receipt($data, $requestId = null)
    {
        $headers = [];
        if ($requestId) {
            $headers[] = "X-Request-ID: {$requestId}";
        }

        $response = $this->sendRequest('/kkt/receipt', $data, $headers);
        if (empty($response['Success'])) {
            throw new Exception\RequestException($response);
        }

        return $response;
    }
}
