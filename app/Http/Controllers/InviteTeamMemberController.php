<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Events\UserHasBeenInvitedToTeam;
use App\Http\Controller;
use App\Http\Requests\InviteTeamMemberRequest;
use App\Models\TeamInvite;
use Illuminate\Http\RedirectResponse;

final class InviteTeamMemberController extends Controller
{
    public function __invoke(InviteTeamMemberRequest $request): RedirectResponse
    {
        /** @var TeamInvite $invite */
        $invite = TeamInvite::query()
            ->updateOrCreate([
                'team_id' => $request->dto()->team,
                'user_id' => $request->user()->getKey(),
                'email' => $request->dto()->email,
            ], [
                'role_id' => $request->dto()->role,
                'default' => false,
            ]);

        UserHasBeenInvitedToTeam::dispatch($invite);

        return to_route('team.show', $request->dto()->team)
            ->with('message', __("Invito inviato a {$request->dto()->email}"));
    }
}
