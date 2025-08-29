<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Override;

/** @mixin Team */
final class TeamResource extends JsonResource
{
    /**
     * @return array<int|string, mixed>
     */
    #[Override]
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->getKey(),
            'name' => $this->getAttributeValue('name'),
            'stripe' => [
                'id' => $this->getAttributeValue('stripe_id'),
            ],
            'subscription' => new SubscriptionResource($this->whenLoaded('subscription')),
            'members' => TeamMemberResource::collection($this->whenLoaded('members')),
            'counts' => [
                'devices' => $this->whenCounted(relationship: 'devices', default: 0),
                'members' => $this->whenCounted(relationship: 'members', default: 0),
                'slideshows' => $this->whenCounted(relationship: 'slideshows', default: 0),
            ],
            'createdAt' => $this->getAttributeValue('created_at'),
            'updatedAt' => $this->getAttributeValue('updated_at'),
            'deletedAt' => $this->getAttributeValue('deleted_at'),
        ];
    }
}
