<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Validators\AuthValidator;
use Laravel\Passport\RefreshTokenRepository;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AuthController extends Controller
{

    public function register(Request $request)
    {
        $validator = AuthValidator::register($request->all());

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->wallet = $request->wallet;
        $user->save();
        return response()->json(['data' => $user],201);
    }

    public function logIn(Request $request)
    {
        $credentials = $request->only(['email', 'password']);
        $validator = AuthValidator::logIn($credentials);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if (!auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        //  Create a new personal access token for the user.
        $tokenData = auth()->user()->createToken('Personal Access Token');
        $token = $tokenData->accessToken;
        $expiration = $tokenData->token->expires_at->diffInSeconds(Carbon::now());

        $cookie = $this->getCookie($token, $expiration);

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => $expiration
        ])->withCookie($cookie);
    }

    private function getCookie($token, $expiration) {
        return cookie(
            config('auth.cookie_name'),     //name
            $token,                         //value
            $expiration,                    //minutes
            null,                           //path
            null,                           //domain
            env('APP_DEBUG') ? false : true,//secure
            false,                           //httpOnly
            false,                          //sameSite
            'Strict'
        );
    }

    public function getUser()
    {
        return response()->json(auth()->user());
    }

    public function logout()
    {
        $token = auth()->user()->token();

        /* --------------------------- revoke access token -------------------------- */
        $token->revoke();
        $token->delete();

        /* -------------------------- revoke refresh token -------------------------- */
        $refreshTokenRepository = app(RefreshTokenRepository::class);
        $refreshTokenRepository->revokeRefreshTokensByAccessTokenId($token->id);

        $cookie = Cookie::forget(config('auth.cookie_name'));

        return response()->json(['message' => 'Logged out successfully'])->withCookie($cookie);
    }

    /* ----------------- get both access_token and refresh_token ---------------- */
    public function loginGrant(Request $request)
    {
        $validator = AuthValidator::logIn($request->all());

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $baseUrl = url('http://host.docker.internal:8080');
        $response = Http::post("{$baseUrl}/oauth/token", [
            'username' => $request->email,
            'password' => $request->password,
            'client_id' => config('passport.password_grant_client.id'),
            'client_secret' => config('passport.password_grant_client.secret'),
            'grant_type' => 'password'
        ]);

        $result = json_decode($response->getBody(), true);
        if (!$response->ok()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return response()->json($result);
    }

    /* -------------------------- refresh access_token -------------------------- */
    public function refreshToken(Request $request)
    {
        $validator = AuthValidator::refreshToken($request->all());

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $baseUrl = url('http://host.docker.internal:8080');
        $response = Http::post("{$baseUrl}/oauth/token", [
            'refresh_token' => $request->refresh_token,
            'client_id' => config('passport.password_grant_client.id'),
            'client_secret' => config('passport.password_grant_client.secret'),
            'grant_type' => 'refresh_token'
        ]);

        $result = json_decode($response->getBody(), true);
        if (!$response->ok()) {
            return response()->json(['error' => $result['error_description']], 401);
        }
        return response()->json($result);
    }
}