<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Controller;
use App\Http\Requests\UpdateTeamRequest;
use App\Http\Resources\TeamResource;
use App\Http\Resources\TeamRoleResource;
use App\Models\Team;
use App\Models\TeamRole;
use App\QueryBuilders\HasMember;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

final class TeamController extends Controller
{
    public function index(Request $request): Response
    {
        $teams = Team::query()
            ->with('subscription')
            ->withCount(['members'])
            ->tap(fn (Builder $query) => new HasMember($request->user()->getKey())($query))
            ->get();

        return Inertia::render('team/index', [
            'teams' => TeamResource::collection($teams),
        ]);
    }

    public function show(Request $request, string $teamId): Response
    {
        $team = Team::query()
            ->with(['subscription', 'members'])
            ->withCount(['members'])
            ->tap(fn (Builder $query) => new HasMember($request->user()->getKey(), $teamId)($query))
            ->firstOrFail();

        $team->getRelationValue('members')->loadMissing('members.role');

        $roles = TeamRole::query()->orderBy('slug')->get();

        return Inertia::render('team/edit', [
            'team' => new TeamResource($team),
            'roles' => TeamRoleResource::collection($roles),
        ]);
    }

    public function update(UpdateTeamRequest $request): RedirectResponse
    {
        Team::query()
            ->whereKey($request->dto()->identifier)
            ->update([
                'name' => $request->dto()->name,
            ]);

        return to_route('team.show', $request->dto()->identifier)
            ->with('message', __('Team updated'));
    }
}
