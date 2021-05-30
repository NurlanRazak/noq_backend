<?php

namespace App\Services\Payment\CloudPayment;

use Illuminate\Support\ServiceProvider;

class CloudPaymentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
		$this->app->bind('cloudpayment_service', function ($app) {
			return $app->make(CloudPaymentService::class);
		});
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
