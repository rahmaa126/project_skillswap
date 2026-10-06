<?php

namespace App\Http\Resources\V1;

use App\Models\AiLog;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin AiLog
 */
class AiLogResource extends JsonResource
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
            'feature' => $this->feature,
            'user_id' => $this->user_id,
            'skill_id' => $this->skill_id,
            'model' => $this->model,
            'prompt_tokens' => (int) $this->prompt_tokens,
            'completion_tokens' => (int) $this->completion_tokens,
            'total_tokens' => (int) ($this->prompt_tokens + $this->completion_tokens),
            'status' => $this->status,
            'error_message' => $this->error_message,
            'created_at' => $this->created_at?->toISOString(),
            'user' => new UserResource($this->whenLoaded('user')),
            'skill' => new SkillResource($this->whenLoaded('skill')),
        ];
    }
}
