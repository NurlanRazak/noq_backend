<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\UserBankCard;

class OrderController extends Controller
{

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
        ]);

        $card = UserBankCard::where('user_id', $user->id)->where('id', $data['card_id'])->firstOrFail();

        $charging = \CloudPayment::chargeToken($card, $order->total_amount);

        return $order;
    }

    public function orderList(Request $request)
    {
        $userId = $request->user()->id;

        $orders = Order::where('user_id', $userId)
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
