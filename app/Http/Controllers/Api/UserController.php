<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Payment\CloudPayment\Model\Required3DS;
use App\Traits\ApiResponser;
use App\Models\UserBankCard;
use App\Models\BookingList;
use App\Models\User;

class UserController extends Controller
{
    use ApiResponser;

    public function updateUserInfo(Request $request)
    {
        $user = User::where('id', $request->user()->id)->update([
            'name' => $request->name,
        ]);
        $user = User::where('id', $request->user()->id)->first();

        return $this->success($user);
    }

    public function updateUserImage(Request $request)
    {
        $user = User::where('id', $request->user()->id)->first();

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $timestamp = str_replace([' ', ':'], '-', \Carbon\Carbon::now()->toDateTimeString());
            $name = $timestamp.'-'.$user->id;
            $user->image = $name;
            $file->move(public_path().'/uploads/users/', $name);
            $user->save();
        }

        return $this->success($user);
    }

    public function saveBooking(Request $request)
    {
        $bookingList = BookingList::create([
            'user_id' => $request->user()->id,
            'terrace' => $request->terrace,
            'people' => $request->people,
            'at_time' => $request->at_time,
            'status' => 1,
        ]);

        return $bookingList;
    }

    public function getUserBookings(Request $request)
    {
        return BookingList::where('user_id', $request->user()->id)->active()->get();
    }

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

    public function post3dSecure(Request $request)
    {
        try {
            $data = $request->toArray();
			$transaction = \CloudPayment::confirm3DS($data['MD'], $data['PaRes']);

			$transaction->createBankcard(true);
			// RefundTestPaymentJob::dispatch($transaction)->delay(now()->addSeconds(5))->onQueue('auto');

			$data = [
				'success' => true,
			];

		} catch (\Exception $e) {
			$data = [
				'success' => false,
				'error_message' => $e->getMessage(),
			];
		}

		return view('post3ds', [
			'data' => $data
		]);
    }
}
