<?php

declare(strict_types=1);

namespace App\Models;

use App\Policies\TeamPolicy;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Ludo237\Traits\ExposeTableProperties;

#[UsePolicy(TeamPolicy::class)]
final class Team extends Model
{
    use ExposeTableProperties, HasUlids, SoftDeletes;

    protected $table = 'teams';

    protected $guarded = ['id'];

    /**
     * @return BelongsToMany<User, $this, TeamMember, 'members'>
     */
    public function members(): BelongsToMany
    {
        return $this
            ->belongsToMany(User::class, TeamMember::tableName())
            ->using(TeamMember::class)
            ->as('members')
            ->withPivot(['role_id', 'joined_at', 'default']);
    }

    /**
     * @return BelongsTo<Tier, $this>
     */
    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Tier::class, 'tier_id');
    }
}
