<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use App\Models\Organization;
use App\Models\Scopes\OrganizationScope;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToOrganization
{
    /**
     * Get the organization that owns the model.
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * Boot the BelongsToOrganization trait.
     */
    protected static function bootBelongsToOrganization(): void
    {
        static::addGlobalScope(new OrganizationScope);

        // Automatically set organization_id when creating
        static::creating(function ($model) {
            if (! $model->organization_id) {
                // If user is authenticated, use their organization_id
                if (auth()->check() && auth()->user()->organization_id) {
                    $model->organization_id = auth()->user()->organization_id;
                }
                // During testing, create an organization if none exists
                elseif (app()->runningUnitTests()) {
                    $organization = Organization::first() ?? Organization::factory()->create();
                    $model->organization_id = $organization->id;
                }
                // During seeding, use the first organization
                elseif (app()->runningInConsole()) {
                    $firstOrg = Organization::first();
                    if ($firstOrg) {
                        $model->organization_id = $firstOrg->id;
                    }
                }
            }
        });
    }
}
