<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Team;
use App\Models\Tier;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Team */
final class TeamResource extends JsonResource
{
    /**
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var Tier|null $subscription */
        $subscription = $this->getRelationValue('subscription');

        return [
            'id' => $this->getKey(),
            'name' => $this->getAttributeValue('name'),
            'tier' => $this->whenLoaded('subscription', [
                'id' => $subscription->getKey(),
                'name' => $subscription->getAttributeValue('name'),
            ]),
            'members' => TeamMemberResource::collection($this->whenLoaded('members')),

            'counts' => [
                'members' => $this->whenCounted(relationship: 'members', default: 0),
            ],

            'createdAt' => $this->getAttributeValue('created_at'),
            'updatedAt' => $this->getAttributeValue('updated_at'),
            'deletedAt' => $this->getAttributeValue('deleted_at'),
        ];
    }
}
