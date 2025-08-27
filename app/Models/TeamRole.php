<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Ludo237\Traits\ExposeTableProperties;

final class TeamRole extends Model
{
    use ExposeTableProperties, HasUlids;

    protected $table = 'team_roles';

    protected $casts = [
        'permissions' => 'json',
    ];
}
