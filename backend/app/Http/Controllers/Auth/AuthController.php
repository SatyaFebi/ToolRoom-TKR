<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function getUserData()
    {
        return response()->json([
            'success' => true,
            'data' => User::all()
        ]);
    }

    public function register(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|unique:users,email',
                'password' => 'required|string|min:8',
                'role_id' => 'required|exists:roles,id'
            ]);
    
            $validated['password'] = Hash::make($validated['password']);

            $user = User::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Registrasi berhasil, silakan login!',
                'data' => $user
            ]);
        } catch (\Exception $e) {
            Log::error('Terdapat kesalahan: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal registrasi, mohon cek kembali data yang anda masukkan!',
                'error' => $e->getMessage()
            ]);
        }

    }

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
                ], 400);
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

             return response()->json([
                'success' => true,
                'message' => 'Login berhasil',
            ], 400);
        }
        
    }

    public function update(Request $request, $id)
    {
        try {
            $user = User::find($id);

            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|unique:users,email,' . $user->id,
                'password' => 'sometimes|string|min:8',
                'role_id' => 'required|exists:roles,id'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $user->name = $request->name;
            $user->email = $request->email;

            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }

            $user->role_id = $request->role_id;

            $user->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil diupdate!',
                'data' => $user
            ], 200);
        } catch (\Exception $e) {
            Log::error('Terdapat kesalahan pada saat update: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal update data, mohon cek kembali data yang anda masukkan!',
                'error' => $e->getMessage(),
            ], 400);
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
