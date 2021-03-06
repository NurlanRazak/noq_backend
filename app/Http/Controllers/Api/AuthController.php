<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use App\Models\User;
use App\Models\VerificationToken;
use Illuminate\Support\Facades\Hash;
use App\Jobs\SendSmsJob;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use App\Notifications\TwoFactorCode;

class AuthController extends Controller
{
    use ApiResponser;

    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'email|required|unique:users',
            'password' => 'required|confirmed',
            'name' => 'required',
        ]);

        $validatedData['password'] = bcrypt($request->password);

        $user = User::create($validatedData);

        $this->timestamps = false;
        $user->two_factor_code = rand(100000, 999999);
        $user->two_factor_expires_at = now()->addMinutes(10);
        $user->save();

        $user->notify(new TwoFactorCode());

        return response()->json([
            'success' => true,
            'message' => 'Registration went successfully',
            'data' => null,
        ], 200);
    }

    public function login(Request $request)
    {
        $loginData = $request->validate([
            'email' => 'email|required',
            'password' => 'required'
        ]);
		$user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                        'success' => false,
                        'message' => 'User not found!'
                    ], 422);
        }

        if (! Hash::check($request->password, $user->password)) {
            return response()->json([
                        'success' => false,
                        'message' => 'Password does not match!'
                    ], 422);
        }

		if ($user->email_verified_at == null) {

            $user->timestamps = false;
            $user->two_factor_code = rand(100000, 999999);
            $user->two_factor_expires_at = now()->addMinutes(10);
            $user->save();

            $user->notify(new TwoFactorCode());

			if ($user->email_verified_at == null) {
				return response()->json([
					'success' => false,
					'message' => 'Confirm your email!'
				], 422);
			}
			return $this->error('Oops something gone wrong', 422);
		}
        if (!auth()->attempt($loginData)) {
            return $this->error('Oops something gone wrong', 422);
        }

        $accessToken = auth()->user()->createToken('API Token')->plainTextToken;

        return $this->success(['user' => auth()->user(), 'access_token' => $accessToken]);
    }
}
