<?php

namespace App\Http\Controllers\Api;

use Illuminate\Auth\Events\Verified;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\User;

class VerifyEmailController extends Controller
{

    public function __invoke(Request $request): RedirectResponse
    {
        $request->validate([
            'two_factor_code' => 'integer|required',
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user) {
            if($request->input('two_factor_code') == $user->two_factor_code)
            {
                $user->resetTwoFactorCode();

                return response()->json([
                    'message' => 'Ваш email успешно активирован',
                    'success' => true,
                ]);

                if ($user->markEmailAsVerified()) {
                    event(new Verified($user));
                }
            }
            return response()->json([
                'message' => 'Введенный вами двухфакторный код не соответствует',
                'success' => false,
            ]);
        }
    }
}
