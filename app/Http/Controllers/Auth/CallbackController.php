<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

class CallbackController extends Controller
{
    public function spotifyRedirect(): RedirectResponse
    {
        return Socialite::driver('spotify')->scopes([
            'user-top-read',
            'playlist-modify-public',
        ])->redirect();
    }

    public function spotifyCallback(Request $request): RedirectResponse
    {
        try {
            $socialiteUser = Socialite::driver('spotify')->user();
        } catch (Throwable $e) {
            return redirect()->route('/');
        }

        $user = User::updateOrCreate([
            'spotify_id' => $socialiteUser->id,
        ], [
            'name' => $socialiteUser->name,
            'email' => $socialiteUser->email,
            'avatar' => $socialiteUser->avatar,
            'spotify_token' => $socialiteUser->token,
            'spotify_refresh_token' => $socialiteUser->refreshToken,
        ]);

        if ($user->wasRecentlyCreated) {
            event(new Registered($user));
        }

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
