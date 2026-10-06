<?php

namespace App\Http\Resources\V1;

use App\Models\SkillCategory;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin SkillCategory
 */
class SkillCategoryResource extends JsonResource
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
            'skills_count' => $this->skills_count ?? ($this->relationLoaded('skills') ? $this->skills->count() : null),
            'skills' => SkillResource::collection($this->whenLoaded('skills')),
        ];
    }
}
