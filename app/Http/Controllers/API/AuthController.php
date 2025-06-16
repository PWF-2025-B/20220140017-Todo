<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Exception;

class AuthController extends Controller
{
    /**
     * Login user dengan email dan password.
     */
    public function login(Request $request)
    {
        // Validasi input login
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        try {
            // Attempt login
            if (!$token = Auth::guard('api')->attempt($credentials)) {
                return response()->json([
                    'status_code' => 401,
                    'message' => 'Email atau password salah.',
                ], 401);
            }

            $user = Auth::guard('api')->user();

            return response()->json([
                'status_code' => 200,
                'message' => 'Login berhasil.',
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'is_admin' => (bool) $user->is_admin,
                    ],
                    'token' => $token,
                ]
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'status_code' => 500,
                'message' => 'Terjadi kesalahan saat login.',
                // 'error' => $e->getMessage(), // Uncomment hanya untuk debugging
            ], 500);
        }
    }

    /**
     * Login pengguna yang sedang login.
     */
    public function logout(Request $request)
    {
        try {
            $token = JWTAuth::getToken();

            if (!$token) {
                return response()->json([
                    'status_code' => 400,
                    'message' => 'Token tidak ditemukan.',
                ], 400);
            }

            JWTAuth::invalidate($token); // Hapus token
            Auth::guard('api')->logout(); // Logout guard API

            return response()->json([
                'status_code' => 200,
                'message' => 'Logout berhasil. Token telah dihapus.',
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'status_code' => 500,
                'message' => 'Gagal logout. Terjadi kesalahan.',
                // 'error' => $e->getMessage(), // Uncomment jika butuh
            ], 500);
        }
    }
}
