<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\Uid\Ulid;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // Cek apakah ada parameter 'error' dari Google OAuth
        if ($request->has('error') && $request->get('error') === 'access_denied') {
            // Jika pengguna membatalkan login, arahkan kembali ke halaman utama
            return redirect('/')->with('error', 'Login dengan Google dibatalkan.');
        }
    
        try {
            // Lakukan autentikasi dengan Google
            $loginUser = Socialite::driver('google')->stateless()->user();
    
            // Cek apakah user sudah ada berdasarkan email
            $user = User::where('email', $loginUser->email)->first();
    
            if (!$user) {
                // Buat user baru jika belum ada
                $user = User::create([
                    'id' => Ulid::generate(),
                    'name' => $loginUser->name,
                    'email' => $loginUser->email,
                    'google_id' => $loginUser->id,
                ]);
    
                // Kirim notifikasi selamat datang
                Notification::create([
                    'id' => Ulid::generate(),
                    'title' => 'Selamat Bergabung dengan Platform Tech Radar Kami!',
                    'message' => "Akun Anda telah berhasil dibuat. Kini Anda dapat mengakses dan memanfaatkan fitur Tech Radar untuk memonitor teknologi terbaru, menganalisis tren, dan mengelola inovasi di perusahaan Anda. Jangan ragu untuk mulai menjelajahi semua fitur yang tersedia!",
                    'user_id' => $user->id,
                    'is_read' => false,
                ]);
            }
    
            // Login user
            Auth::login($user);
    
            // Redirect ke halaman utama dengan pesan sukses
            return redirect('/index')->with('login_success', "Hey there! You are in. Let's get started!");
    
        } catch (\Exception $e) {
            // Jika ada kesalahan lain, arahkan kembali ke halaman login dengan pesan error
            return redirect('/login')->with('error', 'Terjadi kesalahan saat mencoba login dengan Google.');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
