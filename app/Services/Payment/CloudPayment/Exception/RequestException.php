<?php

namespace App\Services\Payment\CloudPayment\Exception;

class RequestException extends BaseException
{
    public function __construct($response)
    {
        parent::__construct($response['Message']);
    }
}
