<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\TeamRole;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin TeamRole */
final class TeamRoleResource extends JsonResource
{
    /**
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->getKey(),
            'slug' => $this->getAttributeValue('slug'),
            'name' => $this->getAttributeValue('name'),
            'permissions' => $this->getAttributeValue('permissions'),
            'createdAt' => $this->getAttributeValue('created_at'),
            'updatedAt' => $this->getAttributeValue('updated_at'),
        ];
    }
}
