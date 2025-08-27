<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\PersonalAccessToken;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\Sanctum;
use SocialiteProviders\GitHub\Provider as GithubProvider;
use SocialiteProviders\GitLab\Provider as GitlabProvider;
use SocialiteProviders\Google\Provider as GoogleProvider;
use SocialiteProviders\Manager\SocialiteWasCalled;

final class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        Date::use(CarbonImmutable::class);

        Event::listen(function (SocialiteWasCalled $event) {
            $event->extendSocialite('github', GithubProvider::class);
            $event->extendSocialite('gitlab', GitlabProvider::class);
            $event->extendSocialite('google', GoogleProvider::class);
        });

        Model::preventLazyLoading();

        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);

        if ($this->app->environment(['production', 'prod'])) {
            URL::forceScheme('https');
        }

        Vite::useAggressivePrefetching();
        Vite::prefetch();
    }
}
