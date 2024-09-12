<?php

namespace App\Http\Controllers;

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
        return redirect('/')->with('login_success', 'Jelajahi Perusahaan Dan Tekhnologi Terkini!');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/testHome');
    }
}
