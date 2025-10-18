<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\BelongsToOrganization;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

final class Institution extends Model
{
    /** @use HasFactory<\Database\Factories\InstitutionFactory> */
    use BelongsToOrganization;

    use HasFactory;
    use HasUlids;
    use SoftDeletes;

    protected $fillable = [
        'organization_id',
        'country_id',
        'name',
        'logo',
        'description',
        'institution_type',
        'city',
        'state',
        'address',
        'website',
        'email',
        'phone',
        'rankings',
        'accreditation',
        'facilities',
        'campus_life',
        'established_year',
        'commission_rate',
        'commission_type',
        'contact_persons',
        'is_partner',
        'is_active',
    ];

    public function country(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function courses(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Course::class);
    }

    protected function casts(): array
    {
        return [
            'rankings' => 'array',
            'facilities' => 'array',
            'contact_persons' => 'array',
            'commission_rate' => 'decimal:2',
            'is_partner' => 'boolean',
            'is_active' => 'boolean',
        ];
    }
}
