<?php

declare(strict_types=1);

namespace App\DataTransferObjects;

use App\Contracts\DataTransferObject;

final readonly class InviteTeamMemberDto implements DataTransferObject
{
    public function __construct(
        public string $team,
        public string $email,
        public string $role
    ) {}
}
