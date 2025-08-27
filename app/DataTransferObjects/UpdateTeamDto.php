<?php

declare(strict_types=1);

namespace App\DataTransferObjects;

use App\Contracts\DataTransferObject;

final readonly class UpdateTeamDto implements DataTransferObject
{
    public function __construct(
        public string $identifier,
        public string $name,
    ) {}
}
