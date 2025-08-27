<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Http\Resources\TeamResource;
use App\Http\Resources\UserResource;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Inertia\Middleware;
use Tighten\Ziggy\Ziggy;

final class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $teams = [];
        /** @var User|null $user */
        $user = $request->user();

        if ($user) {
            $user->loadMissing(['memberOfTeams.subscription']);
            $user = new UserResource($user);
            /** @var Collection<int, Team> $teams */
            $teams = $user->getRelationValue('memberOfTeams');
            $teams = TeamResource::collection($teams);
        }

        return [
            ...parent::share($request),
            'name' => config('app.name'),
            'csrfToken' => csrf_token(),
            'auth' => [
                'user' => $user,
                'teams' => $teams,
            ],
            'ziggy' => fn (): array => [
                ...(new Ziggy)->toArray(),
                'location' => $request->url(),
            ],
            'sidebarOpen' => ! $request->hasCookie('sidebar_state') || $request->cookie('sidebar_state') === 'true',
            'flash' => [
                'message' => fn () => $request->session()->get('message'),
                'success' => fn () => $request->session()->get('success'),
                'error' => fn () => $request->session()->get('error'),
            ],
        ];
    }
}
