<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenExpiredException;
use Illuminate\Validation\Rule;
use App\Models\User;

class AuthController extends Controller
{

    /**
     * Register user baru
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|string|unique:users',
            'password' => 'required|string|min:8',
            'role_id' => 'required|exists:roles,id'
        ], [

            'name.required' => 'Nama wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Email harus valid',
            'email.unique' => 'Email sudah ada di database!',
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 8 karakter',
            'role_id.required' => 'Role wajib diisi',
            'role_id.exists' => 'Role tidak valid'
        ]);

        try {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => bcrypt($validated['password']),
                'role_id' => $validated['role_id']
            ]);

            $token = JWTAuth::fromUser($user);

            return response()->json([
                'success' => true,
                'message' => 'Registrasi berhasil!',
                'token' => $token,
                'token_type' => 'Bearer',
                'expires_in' => JWTAuth::factory()->getTTL() * 60,
                'users' => $user
            ], 201);
        } catch (\Exception $e) {
            Log::error('Registrasi gagal dengan error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada saat registrasi.'
            ]);
        }
    }

    /**
     * Update profil yang sedang login
     */
    public function update(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada user yang terautentikasi. Mohon refresh halaman.'
                ], 401);
            }

            $validated = $request->validate([
                'name' => 'sometimes|string',
                'email' => [
                    'sometimes',
                    'string',
                    'email',
                    Rule::unique('users')->ignore($user->id),
                ],
                'password' => 'nullable|string|min:8'
            ]);

            if (isset($validated['name'])) {
                $user->name = $validated['name'];
            }

            if (isset($validated['email'])) {
                $user->email = $validated['email'];
            }

            if (!empty($validated['password'])) {
                $user->password = bcrypt($validated['password']);
            }

            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Profile berhasil diperbarui',
                'user' => $user
            ], 200);
        } catch (JWTException $e) {
            Log::error('Kesalahan JWT saat update: ' . $e);

            return response()->json([
                'success' => false,
                'message' => 'Token tidak valid atau sudah kedaluwarsa',
            ], 401);
        } catch (\Exception $e) {
            Log::error('Kesalaha saat update profile: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat update profil'
            ], 500);
        }
    }

    /**
     * Hapus User
     */
    public function editUser(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => [
                'required',
                'email',
                Rule::unique('tkr_inventory_management.users', 'email')->ignore($id)
            ],
            'role_id' => [
                'required',
                Rule::exists('tkr_inventory_management.roles', 'id')
            ],
            'password' => 'nullable|min:8'
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah ada di database.',
            'name.required' => 'Nama wajib diisi.',
            'role_id.required' => 'Role wajib dipilih.',
            'role_id.exists' => 'Role tidak valid.',
            'password.min' => 'Password minimal 8 karakter.'
        ]);

        $user = User::findOrFail($id);

        if (!empty($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'User berhasil diupdate',
            'user' =>  $user->only(['id', 'name', 'email', 'role_id'])
        ], 200);
    }

    /**
     * Hapus User
     */
    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
    }

    /**
     * Login user dan return JWT token
     */
    public function login(Request $request)
    {
        try {
            $credentials = $request->validate([
                'email'    => 'required|email',
                'password' => 'required|string|min:8',
            ], [
                'email.required' => 'Email wajib diisi',
                'email.email' => 'Email harus valid',
                'password.required' => 'Password wajib diisi',
                'password.min' => 'Password minimal 8 karakter',
            ]);
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
                'message' => 'Login gagal. Cek kredensial kembali',
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
     * Refresh token
     */
    public function refresh()
    {
        try {
            $newToken = JWTAuth::parseToken()->refresh();
            return response()->json([
                'success' => true,
                'token' => $newToken
            ], 200);
        } catch (JWTException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal refresh token',
            ], 401);
        }
    }

    /**
     * Get data user yang sedang login
     */
    public function me()
    {
        try {
            $start = microtime(true);
            $payload = JWTAuth::parseToken()->getPayload();
            $user = [
                'id' => $payload->get('sub'),
                'name' => $payload->get('name') ?? null,
                'email' => $payload->get('email') ?? null
            ];
            Log::info('Waktu autentikasi : ' . (microtime(true) - $start) . ' detik');

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
        } catch (TokenExpiredException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Token expired, silakan refresh token'
            ], 401);
        } catch (JWTException $e) {
            Log::error($e);
            return response()->json([
                'success' => false,
                'message' => 'Token tidak valid',
            ], 403);
        }
    }

    /**
     * Fetch semua user
     */
    public function getUserData()
    {
        $user = User::all();

        return response()->json([
            'success' => true,
            'data' => $user
        ]);
    }
}
