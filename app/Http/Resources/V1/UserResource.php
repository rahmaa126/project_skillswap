<?php

namespace App\Http\Resources\V1;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin User
 */
class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
            'is_active' => (bool) $this->is_active,
            'email_verified_at' => $this->email_verified_at?->toISOString(),
            'last_login_at' => $this->last_login_at?->toISOString(),
            'profile' => [
                'avatar_url' => $this->profile?->avatar_url,
                'bio' => $this->profile?->bio,
                'city' => $this->profile?->city,
                'phone' => $this->profile?->phone,
                'avg_rating' => $this->profile ? (float) $this->profile->avg_rating : 0.0,
                'total_swaps' => $this->profile ? (int) $this->profile->total_swaps : 0,
            ],
            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}
