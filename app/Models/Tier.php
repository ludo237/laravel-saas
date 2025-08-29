<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Number;
use Ludo237\Traits\ExposeTableProperties;

final class Tier extends Model
{
    use ExposeTableProperties, HasUlids;

    protected $table = 'tiers';

    protected $guarded = ['id'];

    /**
     * @return Attribute<string,int>
     */
    public function price(): Attribute
    {
        return Attribute::make(
            get: fn (int $value) => Number::currency($value / 100, in: 'EUR'),
            set: function (string|int|float $value): string|int {
                if (! is_numeric($value)) {
                    return $value;
                }

                // If it's a float or has decimal places (euro amount), convert to cents
                if (is_float($value) || (is_string($value) && str_contains($value, '.'))) {
                    return (int) round((float) $value * 100);
                }

                // Otherwise, assume it's already in cents
                return (int) $value;
            },
        );
    }

    /**
     * @return HasMany<Subscription, $this>
     */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }
}
