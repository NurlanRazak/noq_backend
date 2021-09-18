<?php

namespace App\Http\Controllers\Api;

use Illuminate\Auth\Events\Verified;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\User;

class VerifyEmailController extends Controller
{

    public function verifyEmail(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if ($user) {
            if($request->input('two_factor_code') == $user->two_factor_code)
            {
                $user->resetTwoFactorCode();
				$user->markEmailAsVerified();
                return response()->json([
                    'message' => 'Your email has been successfully activated',
                    'success' => true,
                ]);

            }
            return response()->json([
                'message' => 'The two-factor code you entered does not match',
                'success' => false,
            ]);
        }
    }
}
