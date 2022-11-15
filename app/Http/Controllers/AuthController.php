<?php

namespace App\Http\Controllers;


use App\Http\Requests\LoginRequest;
use App\Http\Requests\RefreshTokenRequest;
use App\Models\RefreshToken;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class AuthController extends Controller
{

    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();

        if (!$accessToken = auth()->attempt($credentials)) {
            return $this->errorResponse('Email or password is incorrect');
        }

        $user = auth()->user();

        $refreshToken = $this->createRefreshToken($user);

        return $this->respondWithToken($accessToken, $refreshToken);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(status: 204);
    }


    /**
     * Refresh access token
     *
     * @param RefreshTokenRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh(RefreshTokenRequest $request)
    {
        $refreshToken = $request->refresh_token;

        $refreshToken = RefreshToken::token($refreshToken)->first();

        if(is_null($refreshToken) || $refreshToken->user_id != auth()->id()) {
            return $this->errorResponse('Invalid refresh token');
        }

        $accessToken = auth()->login(auth()->user());

        $newRefreshToken = $this->createRefreshToken(auth()->user());

        return $this->respondWithToken($accessToken, $newRefreshToken);
    }


    /**
     * Login response
     *
     * @param $accessToken
     * @param $refreshToken
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($accessToken, $refreshToken)
    {
        return response()->success([
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken,
            'token_type' => 'Bearer',
            'access_expires_in' => env('JWT_TTL') * 60,
            'is_user' => auth()->user()->isUser()
        ]);
    }

    /**
     * New refresh token record in DB
     *
     * @param $user
     * @return string
     */
    private function createRefreshToken($user)
    {
        $user->refreshToken()?->delete();

        $refreshToken = $this->generateRefreshToken();

        $refreshObject = new RefreshToken(['token' => $refreshToken]);

        $user->refreshToken()->save($refreshObject);

        return $refreshToken;
    }

    /**
     * Random string generator
     *
     * @return string
     */
    private function generateRefreshToken(): string
    {
        return Str::random(150);
    }

}
