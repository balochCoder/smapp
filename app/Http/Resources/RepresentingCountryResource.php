<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class RepresentingCountryResource extends JsonResource
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
            'country_id' => $this->country_id,
            'monthly_living_cost' => $this->monthly_living_cost,
            'currency' => $this->currency,
            'visa_requirements' => $this->visa_requirements,
            'part_time_work_details' => $this->part_time_work_details,
            'country_benefits' => $this->country_benefits,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
            'country' => $this->when($this->country, [
                'id' => $this->country?->id,
                'name' => $this->country?->name,
                'flag' => $this->country?->flag,
            ]),
            'rep_country_statuses' => RepCountryStatusResource::collection($this->whenLoaded('repCountryStatuses')),
            'application_processes' => $this->when(
                isset($this->application_processes) && $this->application_processes !== null,
                fn () => $this->application_processes
            ),
        ];
    }
}
