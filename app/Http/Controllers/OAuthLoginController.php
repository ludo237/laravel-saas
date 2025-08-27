<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Controller;
use App\Models\OAuthProvider;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpFoundation\RedirectResponse as SymfonyRedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

final class OAuthLoginController extends Controller
{
    private const SUPPORTED_PROVIDERS = ['github', 'gitlab', 'google'];

    public function redirect(string $provider): SymfonyRedirectResponse
    {
        if (! in_array($provider, self::SUPPORTED_PROVIDERS)) {
            abort(404, 'Provider not supported');
        }

        return Socialite::driver($provider)->redirect();
    }

    public function callback(string $provider): RedirectResponse
    {
        if (! in_array($provider, self::SUPPORTED_PROVIDERS)) {
            abort(404, 'Provider not supported');
        }

        /** @var \Laravel\Socialite\Two\User $socialiteUser */
        $socialiteUser = Socialite::driver($provider)->user();

        if (Auth::check()) {
            // User is logged in - adding provider to existing account
            return $this->handleProviderConnection($socialiteUser, $provider);
        }

        // User is guest - login/registration flow
        return $this->handleUserLogin($socialiteUser, $provider);

    }

    public function disconnect(Request $request, string $provider): RedirectResponse
    {
        if (! in_array($provider, self::SUPPORTED_PROVIDERS)) {
            abort(404, 'Provider not supported');
        }

        $user = Auth::user();

        $deleted = OAuthProvider::query()
            ->where('user_id', $user->getKey())
            ->where('provider_name', $provider)
            ->delete();

        if ($deleted) {
            return to_route('profile.show')
                ->with('success', "Successfully disconnected your {$provider} account.");
        }

        return to_route('profile.show')
            ->with('error', "No {$provider} account found to disconnect.");
    }

    private function handleProviderConnection(\Laravel\Socialite\Two\User $socialiteUser, string $provider): RedirectResponse
    {
        $user = Auth::user();

        // Check if this provider account is already connected to another user
        $existingProvider = OAuthProvider::query()
            ->where('provider_name', $provider)
            ->where('provider_id', $socialiteUser->getId())
            ->first();

        if ($existingProvider && $existingProvider->getAttributeValue('user_id') !== $user->getKey()) {
            return to_route('profile.show')
                ->with('error', "This {$provider} account is already connected to another user.");
        }

        // Check if user already has this provider connected
        $userProvider = OAuthProvider::query()
            ->where('user_id', $user->getKey())
            ->where('provider_name', $provider)
            ->first();

        if ($userProvider) {
            return to_route('profile.show')
                ->with('error', "You already have a {$provider} account connected.");
        }

        // Create the new provider connection
        OAuthProvider::query()->create([
            'user_id' => $user->getKey(),
            'provider_name' => $provider,
            'provider_id' => $socialiteUser->getId(),
            'username' => $socialiteUser->getNickname(),
            'token' => $socialiteUser->token,
            'email' => $socialiteUser->getEmail(),
            'refresh_token' => $socialiteUser->refreshToken,
            'expired_at' => Date::now()->addSeconds($socialiteUser->expiresIn),
        ]);

        return to_route('profile.show')
            ->with('success', "Successfully connected your {$provider} account!");
    }

    private function handleUserLogin(\Laravel\Socialite\Two\User $socialiteUser, string $provider): RedirectResponse
    {
        /** @var OAuthProvider|null $authProvider */
        $authProvider = OAuthProvider::query()
            ->with('user')
            ->where('provider_name', '=', $provider)
            ->where('provider_id', '=', $socialiteUser->getId())
            ->first();

        if (! $authProvider) {
            // Create the user first
            $user = User::query()
                ->firstOrCreate([
                    'email' => $socialiteUser->getEmail(),
                ], [
                    'email_verified_at' => Date::now(),
                    'password' => Str::password(),
                    'name' => $socialiteUser->getName(),
                ]);
            // Create the provider
            OAuthProvider::query()
                ->create([
                    'provider_name' => $provider,
                    'provider_id' => $socialiteUser->getId(),
                    'user_id' => $user->getKey(),
                    'username' => $socialiteUser->getNickname(),
                    'token' => $socialiteUser->token,
                    'email' => $socialiteUser->getEmail(),
                    'refresh_token' => $socialiteUser->refreshToken,
                    'expired_at' => Date::now()->addSeconds($socialiteUser->expiresIn),
                ]);
        } else {
            // Provider exists so does the user
            $user = $authProvider->getRelationValue('user');
        }

        Auth::loginUsingId($user->getKey());

        return to_route('dashboard')->withCookie('last_login_with', $provider, 60 * 24 * 30);
    }
}
