<?php

namespace App\Http\Resources\V1;

use App\Models\SwapSession;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin SwapSession
 */
class SwapSessionResource extends JsonResource
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
            'match_id' => $this->match_id,
            'requester_id' => $this->requester_id,
            'partner_id' => $this->partner_id,
            'requester_skill_id' => $this->requester_skill_id,
            'partner_skill_id' => $this->partner_skill_id,
            'status' => $this->status,
            'scheduled_at' => $this->scheduled_at?->toISOString(),
            'started_at' => $this->started_at?->toISOString(),
            'completed_at' => $this->completed_at?->toISOString(),
            'created_at' => $this->created_at?->toISOString(),
            'requester' => new UserResource($this->whenLoaded('requester')),
            'partner' => new UserResource($this->whenLoaded('partner')),
            'requester_skill' => new SkillResource($this->whenLoaded('requesterSkill')),
            'partner_skill' => new SkillResource($this->whenLoaded('partnerSkill')),
        ];
    }
}
