<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\BelongsToOrganization;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

final class RepresentingCountry extends Model
{
    /** @use HasFactory<\Database\Factories\RepresentingCountryFactory> */
    use BelongsToOrganization;

    use HasFactory;
    use HasUlids;
    use SoftDeletes;

    protected $fillable = [
        'organization_id',
        'country_id',
        'monthly_living_cost',
        'currency',
        'visa_requirements',
        'part_time_work_details',
        'country_benefits',
        'is_active',
    ];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function repCountryStatuses(): HasMany
    {
        return $this->hasMany(RepCountryStatus::class, 'representing_country_id')->orderBy('order');
    }

    public function institutions(): HasManyThrough
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
