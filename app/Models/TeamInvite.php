<?php

declare(strict_types=1);

namespace App\Models;

use App\Policies\TeamInvitePolicy;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Ludo237\Traits\ExposeTableProperties;

#[UsePolicy(TeamInvitePolicy::class)]
final class TeamInvite extends Model
{
    use ExposeTableProperties, HasUlids;

    protected $table = 'team_invites';

    protected $guarded = ['id'];

    /**
     * @return BelongsTo<Team, $this>
     */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo<TeamRole, $this>
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(TeamRole::class);
    }
}
