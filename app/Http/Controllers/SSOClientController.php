<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\User;

class SSOClientController extends Controller
{
    public function handleCallback(Request $request)
    {
        // Tangkap token (mendukung parameter 'sso_token' atau 'token')
        $ssoToken = $request->input('sso_token') ?? $request->input('token');
        $secret = env('SSO_CLIENT_SECRET');

        if (!$ssoToken) {
            return redirect()->route('login')->withErrors(['msg' => 'Token SSO tidak ditemukan.']);
        }

        // Request verifikasi token ke API SSO Server
        $response = Http::post(env('SSO_SERVER_URL', 'http://127.0.0.1:3000') . '/api/sso/verify', [
            'sso_token' => $ssoToken,
            'secret' => $secret,
        ]);

        if ($response->successful() && $response->json('success')) {
            $userData = $response->json('data.user');

            // Cari atau buat user otomatis berdasarkan email dari SSO
            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'role' => $userData['role'] ?? 'user',
                    'password' => bcrypt(Str::random(16)), // Password acak
                ]
            );

            // Auto Login
            Auth::login($user);

            // Redirect ke halaman utama / dashboard Procurement
            return redirect('/')->with('success', 'Berhasil masuk via SSO!');
        }

        $errorMessage = $response->json('message') ?? 'Verifikasi SSO gagal atau token kedaluwarsa.';
        return redirect()->route('login')->withErrors(['msg' => 'Gagal SSO: ' . $errorMessage]);
    }
}
