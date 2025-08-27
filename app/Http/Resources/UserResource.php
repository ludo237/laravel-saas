<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin User */
final class UserResource extends JsonResource
{
    /**
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->getKey(),
            'name' => $this->getAttributeValue('name'),
            'shortName' => $this->getAttributeValue('short_name'),
            'email' => $this->getAttributeValue('email'),
            'emailVerified' => $this->whenNotNull($this->getAttributeValue('email_verified_at')),
            'phone' => $this->whenNotNull($this->getAttributeValue('phone')),
            'phoneVerified' => $this->whenNotNull($this->getAttributeValue('phone_verified_at')),
            'createdAt' => $this->getAttributeValue('created_at'),
            'updateAt' => $this->getAttributeValue('updated_at'),
            'deletedAt' => $this->getAttributeValue('deleted_at'),

            'memberOf' => TeamResource::collection($this->whenLoaded('memberOfTeams')),
        ];
    }
}
