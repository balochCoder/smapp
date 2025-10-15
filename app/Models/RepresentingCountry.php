<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

final class RepresentingCountry extends Model
{
    /** @use HasFactory<\Database\Factories\RepresentingCountryFactory> */
    use HasFactory;

    use HasUlids;

    protected $fillable = [
        'country_id',
        'monthly_living_cost',
        'visa_requirements',
        'part_time_work_details',
        'country_benefits',
        'is_active',
    ];

    public function country(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function applicationProcesses(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(ApplicationProcess::class)->orderBy('order');
    }

    public function institutions(): \Illuminate\Database\Eloquent\Relations\HasManyThrough
    {
        return $this->hasManyThrough(Institution::class, Country::class, 'id', 'country_id', 'country_id', 'id');
    }

    protected function casts(): array
    {
        return [
            'monthly_living_cost' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }
}
