<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Ludo237\Traits\ExposeTableProperties;

final class OAuthProvider extends Model
{
    use ExposeTableProperties, HasUlids;

    protected $table = 'oauth_providers';

    protected $guarded = ['id'];

    protected $casts = [
        'expires_at' => 'date',
    ];

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
