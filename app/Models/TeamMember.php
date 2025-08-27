<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Ludo237\Traits\ExposeTableProperties;

final class TeamMember extends Pivot
{
    use ExposeTableProperties;

    public $timestamps = false;

    protected $table = 'teams_members';

    protected $casts = [
        'joined_at' => 'datetime',
        'default' => 'boolean',
    ];

    /**
     * @return BelongsTo<TeamRole, $this>
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(TeamRole::class);
    }

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
}
