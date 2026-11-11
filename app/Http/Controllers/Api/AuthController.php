<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $accessToken = $user->createToken('access_token', ['access-api'], now()->addMinutes(15))->plainTextToken;
        $refreshToken = $user->createToken('refresh_token', ['issue-access-token'], now()->addDays(7))->plainTextToken;

        return response()->json([
            'user' => $user,
            'access_token' => $accessToken,
        ], 201)->cookie('refresh_token', $refreshToken, 60 * 24 * 7, '/api/refresh-token', null, app()->environment('production'), true, false, 'Strict');
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('email', $data['email'])->first();

        if (! $user || ! Hash::check($data['password'], $user->password)) {
            return response()->json([
                'message' => 'Email hoặc mật khẩu không đúng',
            ], 401);
        }

        $accessToken = $user->createToken('access_token', ['access-api'], now()->addMinutes(15))->plainTextToken;
        $refreshToken = $user->createToken('refresh_token', ['issue-access-token'], now()->addDays(7))->plainTextToken;

        return response()->json([
            'user' => $user,
            'access_token' => $accessToken,
        ])->cookie('refresh_token', $refreshToken, 60 * 24 * 7, '/api/refresh-token', null, app()->environment('production'), true, false, 'Strict');
    }

    public function me(Request $request)
    {
        return response()->json([
            'user' => $request->user(),
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Đăng xuất thành công',
        ])->withoutCookie('refresh_token', '/api/refresh-token');
    }

    public function refreshToken(Request $request)
    {
        $refreshToken = $request->cookie('refresh_token');
        $tokenModel = $refreshToken
            ? PersonalAccessToken::findToken($refreshToken)
            : null;
        $user = $tokenModel?->tokenable;

        if (
            ! $tokenModel
            || ! ($user instanceof User)
            || ! $tokenModel->can('issue-access-token')
            || ($tokenModel->expires_at && $tokenModel->expires_at->isPast())
        ) {
            return response()->json(['message' => 'Refresh Token không hợp lệ'], 401)
                ->withoutCookie('refresh_token', '/api/refresh-token');
        }

        $tokenModel->delete();
        $user->tokens()->where('name', 'access_token')->delete();

        $newAccessToken = $user->createToken('access_token', ['access-api'], now()->addMinutes(15))->plainTextToken;
        $newRefreshToken = $user->createToken('refresh_token', ['issue-access-token'], now()->addDays(7))->plainTextToken;

        return response()->json(['access_token' => $newAccessToken])
            ->cookie('refresh_token', $newRefreshToken, 60 * 24 * 7, '/api/refresh-token', null, app()->environment('production'), true, false, 'Strict');
    }
}
