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
        }
        Auth::login($user);
        Notification::create([
            'id' => Ulid::generate(),
            'title' => 'Berhasil Bergabung!',
            'message' => 'Anda Berhasil Bergabung',
            'user_id' => $user->id,
            'is_read' => False      
        ]);
        return redirect('/')->with('login_success', 'Temukan Perusahaan dan Teknologi Terbaru Sekarang!');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/testHome');
    }
}
