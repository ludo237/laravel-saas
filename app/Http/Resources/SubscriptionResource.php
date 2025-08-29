<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Subscription;
use App\Models\Tier;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Override;

/** @mixin Subscription */
final class SubscriptionResource extends JsonResource
{
    /**
     * @return array<int|string, mixed>
     */
    #[Override]
    public function toArray(Request $request): array
    {
        /** @var Tier $tier */
        $tier = $this->loadMissing('tier')
            ->getRelationValue('tier');

        $availableStorage = $tier->getRawOriginal('storage');

        return [
            'id' => $this->getKey(),
            'name' => $tier->getAttributeValue('name'),
            'tierId' => $tier->getKey(),
            'price' => [
                'raw' => $tier->getRawOriginal('price'),
                'formatted' => $tier->getAttributeValue('price'),
            ],
            'expiresAt' => $this->getAttributeValue('expires_at'),
            'cancelledAt' => $this->getAttributeValue('cancelled_at'),
            'createdAt' => $this->getAttributeValue('created_at'),
            'updatedAt' => $this->getAttributeValue('updated_at'),
            'deletedAt' => $this->getAttributeValue('deleted_at'),
        ];
    }
}
