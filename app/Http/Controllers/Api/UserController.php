<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserBankCard;
use App\Services\Payment\CloudPayment\Model\Required3DS;

class UserController extends Controller
{

    public function getUserBankCard(Request $request)
    {
        return UserBankCard::where('user_id', $request->user()->id)->get();
    }

    public function createUserBankCard(Request $request)
    {
        $data = $request->toArray();
        try {
			$result = \CloudPayment::chargeCard(rand(10, 20), request()->ip(), $data['name'], $data['code'], ['AccountId' => $request->user()->id]);
			if ($result instanceof Required3DS) {
				return response()->json([
					'success' => true,
					'secure' => true,
					'transaction_id' => $result->getTransactionId(),
					'token' => $result->getToken(),
					'url' => $result->getUrl(),
				]);
			} else {

				// RefundTestPaymentJob::dispatch($result)->delay(now()->addSeconds(5))->onQueue('auto');
				$result->createBankcard();

				return response()->json([
					'success' => true,
					'secure' => false,
					'transaction_id' => $result->getId(),
				]);
			}
		} catch (\Exception $e) {
			return response()->json([
				'success' => false,
				'secure' => false,
				'error_message' => $e->getMessage(),
			]);
		}
    }

    public function destroyUserBankCard(Request $request)
    {
        $data = $request->toArray();

        UserBankCard::where([
            ['id', $data['id']],
            ['user_id', $request->user()->id],
        ])->delete();
    }
}