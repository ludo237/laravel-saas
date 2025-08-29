<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Ludo237\Traits\ExposeTableProperties;

#[UseFactory(UserFactory::class)]
final class User extends Authenticatable implements MustVerifyEmail
{
    use ExposeTableProperties, HasApiTokens, HasUlids, Notifiable, SoftDeletes;

    protected $guarded = ['id'];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'phone_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * @return Attribute<string,string>
     */
    public function shortName(): Attribute
    {
        return Attribute::make(
            get: static function ($value, $attributes) {
                $name = $attributes['name'];

                $words = explode(' ', $name);
                if (count($words) >= 2) {
                    return mb_strtoupper(
                        mb_substr($words[0], 0, 1, 'UTF-8').mb_substr(end($words), 0, 1, 'UTF-8'),
                        'UTF-8'
                    );
                }

                preg_match_all('#([A-Z]+)#', $name, $capitals);
                if (count($capitals[1]) >= 2) {
                    return mb_substr(implode('', $capitals[1]), 0, 2, 'UTF-8');
                }

                return mb_strtoupper(mb_substr($name, 0, 2, 'UTF-8'), 'UTF-8');
            }
        );
    }

    /**
     * @return HasMany<OAuthProvider, $this>
     */
    public function providers(): HasMany
    {
        return $this->hasMany(OAuthProvider::class);
    }

    /**
     * @return HasOneThrough<Team, TeamMember, $this>
     */
    public function defaultTeam(): HasOneThrough
    {
        return $this->hasOneThrough(
            Team::class,
            TeamMember::class,
            'user_id',     // Foreign key on TeamMember table
            'id',          // Foreign key on Team table
            'id',          // Local key on User table
            'team_id'      // Local key on TeamMember table
        )->where('teams_members.default', true);
    }

    /**
     * @return BelongsToMany<Team, $this, TeamMember, 'memberOfTeams'>
     */
    public function memberOfTeams(): BelongsToMany
    {
        return $this
            ->belongsToMany(Team::class, TeamMember::tableName())
            ->using(TeamMember::class)
            ->as('memberOfTeams')
            ->withPivot(['role_id', 'joined_at', 'default'])
            ->orderByPivot('default', 'desc')
            ->oldest();
    }
}
