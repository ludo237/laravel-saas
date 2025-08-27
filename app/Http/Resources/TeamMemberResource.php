<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\TeamMember;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin TeamMember */
final class TeamMemberResource extends JsonResource
{
    /**
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->getKey(),
            'name' => $this->getAttributeValue('full_name'),
            'email' => $this->getAttributeValue('email'),
            'emailVerified' => $this->whenNotNull($this->getAttributeValue('email_verified_at')),
            'phone' => $this->whenNotNull($this->getAttributeValue('phone')),
            'phoneVerified' => $this->whenNotNull($this->getAttributeValue('phone_verified_at')),
            'joinedAt' => $this->getRelationValue('members')->getAttributeValue('joined_at'),
            'role' => $this->getRelationValue('members')->getRelationValue('role'),
            'default' => $this->getAttributeValue('default'),
        ];
    }
}
