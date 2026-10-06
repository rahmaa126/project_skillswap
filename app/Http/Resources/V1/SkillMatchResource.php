<?php

namespace App\Http\Resources\V1;

use App\Models\SkillMatch;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin SkillMatch
 */
class SkillMatchResource extends JsonResource
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
            'user_a_id' => $this->user_a_id,
            'user_b_id' => $this->user_b_id,
            'skill_a_id' => $this->skill_a_id,
            'skill_b_id' => $this->skill_b_id,
            'match_score' => (float) $this->match_score,
            'status' => $this->status,
            'match_source' => $this->match_source,
            'score_breakdown' => $this->score_breakdown,
            'ai_reason' => $this->ai_reason,
            'created_at' => $this->created_at?->toISOString(),
            'user_a' => new UserResource($this->whenLoaded('userA')),
            'user_b' => new UserResource($this->whenLoaded('userB')),
            'skill_a' => new SkillResource($this->whenLoaded('skillA')),
            'skill_b' => new SkillResource($this->whenLoaded('skillB')),
        ];
    }
}
