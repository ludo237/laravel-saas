<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\TeamMember;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Override;

/** @mixin TeamMember */
final class TeamMemberResource extends JsonResource
{
    /**
     * @return array<int|string, mixed>
     */
    #[Override]
    public function toArray(Request $request): array
    {
        /** @var TeamMember $teamMember */
        $teamMember = $this->getRelationValue('members');

        return [
            'id' => $this->getKey(),
            // This might come handy
            'teamId' => $teamMember->getAttributeValue('team_id'),
            'name' => $this->getAttributeValue('full_name'),
            'email' => $this->getAttributeValue('email'),
            'emailVerified' => $this->whenNotNull($this->getAttributeValue('email_verified_at')),
            'phone' => $this->whenNotNull($this->getAttributeValue('phone')),
            'phoneVerified' => $this->whenNotNull($this->getAttributeValue('phone_verified_at')),
            'joinedAt' => $teamMember->getAttributeValue('joined_at'),
            'role' => $teamMember->getRelationValue('role'),
            'default' => $teamMember->getAttributeValue('default'),
        ];
    }
}
