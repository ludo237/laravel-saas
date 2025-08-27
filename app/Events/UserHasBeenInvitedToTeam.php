<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\TeamInvite;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

final class UserHasBeenInvitedToTeam
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public readonly TeamInvite $invite
    ) {
        $this->invite->loadMissing(['team', 'user', 'role']);
    }
}
