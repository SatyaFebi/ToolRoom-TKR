<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    /**
     * Login user dan return JWT token
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string|min:8',
        ]);

        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                Log::warning('Login gagal untuk email: ' . $credentials['email']);

                return response()->json([
                    'success' => false,
                    'message' => 'Email atau password salah',
                ], 401);
            }

            $user = JWTAuth::user();

            return response()->json([
                'success'    => true,
                'message'    => 'Login berhasil',
                'token'      => $token,
                'token_type' => 'Bearer',
                'expires_in' => JWTAuth::factory()->getTTL() * 60,
                'user'       => $user,
            ], 200);

        } catch (JWTException $e) {
            Log::error('Kesalahan saat membuat token: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat membuat token',
            ], 500);
        }
    }

    /**
     * Logout user (invalidate token)
     */
    public function logout(Request $request)
    {
        try {
            $token = JWTAuth::getToken();

            if (!$token) {
                return response()->json([
                    'success' => false,
                    'message' => 'Token tidak ditemukan',
                ], 400);
            }

            JWTAuth::invalidate($token);

            return response()->json([
                'success' => true,
                'message' => 'Logout berhasil',
            ], 200);

        } catch (JWTException $e) {
            Log::error('Kesalahan saat logout: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat logout',
            ], 500);
        }
    }

    /**
     * Get data user yang sedang login
     */
    public function me()
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User tidak ditemukan atau token tidak valid',
                ], 401);
            }

            return response()->json([
                'success' => true,
                'user'    => $user,
            ], 200);

        } catch (JWTException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Token tidak valid atau sudah kedaluwarsa',
            ], 401);
        }
    }
}