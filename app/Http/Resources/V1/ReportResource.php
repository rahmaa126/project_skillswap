<?php

namespace App\Http\Resources\V1;

use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Report
 */
class ReportResource extends JsonResource
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
            'reporter_id' => $this->reporter_id,
            'reported_id' => $this->reported_id,
            'session_id' => $this->session_id,
            'reason' => $this->reason,
            'status' => $this->status,
            'handled_by' => $this->handled_by,
            'created_at' => $this->created_at?->toISOString(),
            'reporter' => new UserResource($this->whenLoaded('reporter')),
            'reported' => new UserResource($this->whenLoaded('reported')),
            'handler' => new UserResource($this->whenLoaded('handler')),
        ];
    }
}
