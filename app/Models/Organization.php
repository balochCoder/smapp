<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

final class Organization extends Model
{
    /** @use HasFactory<\Database\Factories\OrganizationFactory> */
    use HasFactory;

    use HasUlids;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'domain',
        'email',
        'phone',
        'address',
        'logo',
        'color_scheme',
        'email_settings',
        'subscription_plan',
        'subscription_expires_at',
        'is_active',
        'settings',
    ];

    /**
     * Get all users belonging to this organization.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get all branches belonging to this organization.
     */
    public function branches(): HasMany
    {
        return $this->hasMany(Branch::class);
    }

    /**
     * Get all representing countries for this organization.
     */
    public function representingCountries(): HasMany
    {
        return $this->hasMany(RepresentingCountry::class);
    }

    /**
     * Get all institutions for this organization.
     */
    public function institutions(): HasMany
    {
        return $this->hasMany(Institution::class);
    }

    /**
     * Get all students for this organization.
     */
    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }

    /**
     * Get all leads for this organization.
     */
    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class);
    }

    /**
     * Get all applications for this organization.
     */
    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }

    protected function casts(): array
    {
        return [
            'color_scheme' => 'array',
            'email_settings' => 'array',
            'settings' => 'array',
            'subscription_expires_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }
}
