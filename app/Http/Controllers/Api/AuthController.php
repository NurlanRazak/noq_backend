<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use App\Models\User;
use App\Models\VerificationToken;
use Illuminate\Support\Facades\Hash;
use App\Jobs\SendSmsJob;

class AuthController extends Controller
{
    use ApiResponser;

    protected function generateToken($phone)
    {
        return (string)rand(10 ** (config('lmservice_auth.verification_code_length') - 1), 10 ** config('lmservice_auth.verification_code_length') - 1);
    }

    public function login(Request $request)
    {
        $user = User::where('phone', $request->phone)->first();
        if (!$user) {
            $user = User::create([
                'phone' => $request->phone,
            ]);
        }

        $token = VerificationToken::create([
            'user_id' => $user->id,
            'phone' => $request->phone,
            'token' => $this->generateToken($request->phone),
        ]);

        SendSmsJob::dispatch(
            config('lmservice_auth.sms_service'),
            $token->phone,
            trans('auth.your_code', ['code' => $token->token])
        );

        return $this->success([], trans('admin.token_required'), 200);
    }

    public function verifyPhone(Request $request)
    {
        $phone = $request->phone;
        $token = $request->token;

        $token = VerificationToken::where('phone', $phone)->where('token', $token)->first()
                                   ?? abort(404, trans('auth.token_not_found'));

       $token->user->update([
           'phone' => $phone,
       ]);
       $token->delete();

       return $this->success([
           'token' => $token->user->createToken('API Token')->plainTextToken
       ]);
    }
}
