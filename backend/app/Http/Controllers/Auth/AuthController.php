<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credential = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|min:8|string'
        ]);

        try {
            $user = User::where('email', $credential['email'])->first();

            // Jika user tidak ditemukan
            if (!$user || !Hash::check( $credential['password'], $user->password)) {
                Log::error('Login gagal untuk email : ' . $credential['email']);
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal login, mohon coba kembali'
                ], 401);
            } 
            $user->tokens()->delete(); 
    
            $token = $user->createToken('auth-token')->plainTextToken;
            return response()->json([
                'success' => true,
                'message' => 'Login berhasil',
                'token' => $token,
                'token_type' => 'Bearer',
                'user' => $user
            ], 200);

        } catch (\Exception $e) {
            Log::error('Terdapat kesalahan: ' . $e->getMessage());
        }
        
    }

    public function logout(Request $request)
    {
        try {
            $user = $request->user();
            if ($user) {
                $user->tokens()->delete();
                Log::info('Pengguna ' . $user->email . ' berhasil logout.');

                return response()->json([
                    'success' => true,
                    'message' => 'Berhasil logout',
                ], 200);
            } 
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada pengguna yang terautentikasi',
            ], 401);
            
        } catch (\Exception $e) {
            Log::error('Kesalahan saat logout: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal logout: ' . $e->getMessage(),
            ], 401);
        }
    }
}
