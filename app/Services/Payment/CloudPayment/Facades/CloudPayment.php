<?php

namespace App\Services\Payment\CloudPayment\Facades;

use Illuminate\Support\Facades\Facade;

class CloudPayment extends Facade
{

	protected static function getFacadeAccessor()
	{
		return 'cloudpayment_service';
	}

}
