<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Ludo237\Traits\ExposeTableProperties;

final class Tier extends Model
{
    use ExposeTableProperties, HasUlids;

    protected $table = 'tiers';

    protected $guarded = ['id'];
}
