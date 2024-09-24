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
    public function login()
    {
        $loginUser = Socialite::driver('google')->stateless()->user();
        $user = User::where('email', $loginUser->email)->first();
        if (!$user) {
            $user = User::create([
                'id' => Ulid::generate(),
                'name' => $loginUser->name,
                'email' => $loginUser->email,
                'google_id' => $loginUser->id,
            ]);
            Notification::create([
                'id' => Ulid::generate(),
                'title' => 'Selamat Bergabung dengan Platform Tech Radar Kami!',
                'message' => "Akun Anda telah berhasil dibuat. Kini Anda dapat mengakses dan memanfaatkan fitur Tech Radar untuk memonitor teknologi terbaru, menganalisis tren, dan mengelola inovasi di perusahaan Anda. Jangan ragu untuk mulai menjelajahi semua fitur yang tersedia!",
                'user_id' => $user->id,
                'is_read' => false,
            ]);
        }
        Auth::login($user);
        return redirect('/')->with('login_success', "Hey there! You are in. Lets get started!");
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/testHome');
    }
}
