<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\UserBankCard;
use App\Traits\ApiResponser;

class OrderController extends Controller
{
    use ApiResponser;

    public function createNewOrder(Request $request)
    {
        $user = $request->user();
        $data = $request->toArray();

        $order = Order::create([
            'user_id' => $user->id,
            'products' => $data['products'],
            'to_time' => $data['to_time'] ?? null,
            'total_amount' => $data['total_amount'],
            'payment_status' => 0,
            'delivery_method' => $data['delivery_method'],
            'place_id' => $data['place_id'],
            'table_id' => $data['table_id'] ?? null,
            'comment' => $data['comment'] ?? null,
            'terrace' => $data['terrace'] ?? null,
            'people' => $data['people'] ?? null,
            'at_time' => $data['at_time'] ?? null,
        ]);
        if (isset($data['card_id'])) {
            $card = UserBankCard::where('user_id', $user->id)->where('id', $data['card_id'])->firstOrFail();

            if ($card) {
    			$order->payment_type = 1;
    			$order->save();
    		}
            $charging = \CloudPayment::chargeToken($card, $order->total_amount);

            return $this->success($order, 'Payment done!');
        }

        return $this->success($order, 'Order sent!');
    }

    public function orderList(Request $request)
    {
        $userId = $request->user()->id;

        $orders = Order::where('user_id', $userId)
			->with('table')
			->latest()
            ->get();

        return $orders;
    }

    public function orderById(Request $request, $id)
    {
        $order = Order::where('id', $id)->with(['place'])->first();

        return $order;
    }
}
