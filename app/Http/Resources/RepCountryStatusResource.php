<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class RepCountryStatusResource extends JsonResource
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
            'status_name' => $this->status_name,
            'custom_name' => $this->custom_name,
            'notes' => $this->notes,
            'order' => $this->order,
            'is_active' => $this->is_active,
            'sub_statuses' => SubStatusResource::collection($this->whenLoaded('subStatuses')),
        ];
    }
}
