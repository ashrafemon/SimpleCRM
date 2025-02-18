<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\Token;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(LoginRequest $loginRequest)
    {
        try {
            if (! $doc = User::query()->where(['email' => $loginRequest->input('email')])->first()) {
                return validateError(['email' => ['Sorry, User not found...']], true);
            }

            if (! Hash::check($loginRequest->input('password'), $doc->password)) {
                return validateError(['email' => 'Password not matched'], true);
            }

            $token = $this->guard()->claims(['id' => $doc->id, 'name' => $doc->name, 'email' => $doc->email, 'role' => $doc->role])->attempt($loginRequest->validated());
            Token::query()->create(['token' => $token, 'expire_at' => now()->addMinutes(30)]);

            return entityResponse($token, 201, 'success', 'Login successful');
        } catch (Exception $e) {
            return serverError($e);
        }
    }

    public function logout()
    {
        try {
            $token = explode(' ', request()->header('authorization'))[1];
            Token::query()->where(['token' => $token])->update(['is_blacklist' => 1]);
            return messageResponse('Logout successful', 200, 'success');
        } catch (Exception $e) {
            return serverError($e);
        }
    }

    public function guard()
    {
        return Auth::guard();
    }
}
