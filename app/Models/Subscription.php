<?php

declare(strict_types=1);

namespace App\Models;

use App\Policies\SubscriptionPolicy;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Ludo237\Traits\ExposeTableProperties;

#[UsePolicy(SubscriptionPolicy::class)]
final class Subscription extends Model
{
    use ExposeTableProperties, HasUlids, SoftDeletes;

    protected $table = 'subscriptions';

    protected $guarded = ['id'];

    protected $casts = [
        'expires_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    /**
     * @return BelongsTo<Team, $this>
     */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * @return BelongsTo<Tier, $this>
     */
    public function tier(): BelongsTo
    {
        return $this->belongsTo(Tier::class);
    }
}
