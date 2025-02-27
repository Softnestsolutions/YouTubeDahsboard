<?php

namespace App\Http\Middleware;

use Closure;
use Google\Client;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class RefreshGoogleToken
{
    public function handle($request, Closure $next)
    {
        $user = Auth::user();

        if ($user && $user->google_refresh_token) {
            $client = new Client();
            $client->setClientId(env('GOOGLE_CLIENT_ID'));
            $client->setClientSecret(env('GOOGLE_CLIENT_SECRET'));
            $client->setRedirectUri(env('GOOGLE_REDIRECT_URI'));

            $client->refreshToken($user->google_refresh_token);
            $newToken = $client->getAccessToken();

            session(['google_access_token' => $newToken]);

            // Update user with new token
            $user->update(['google_refresh_token' => $client->getRefreshToken()]);
        }

        return $next($request);
    }
}
