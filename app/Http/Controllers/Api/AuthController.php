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

        return $this->success($user->email);
    }

    public function login(Request $request)
    {
        $loginData = $request->validate([
            'email' => 'email|required',
            'password' => 'required'
        ]);
		$user = User::where('email', $request->email)->first();

		if (! $user || ! Hash::check($request->password, $user->password) || $user->email_verified_at == null) {
			if ($user->email_verified_at == null) {
				return response()->json([
					'success' => false,
					'message' => 'Подтвердите свой email'
				], 422);
			}
			if (!$user) {
				return response()->json([
					'success' => false,
					'message' => 'Пользователь не найден!'
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
