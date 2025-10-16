<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

final class RepCountryStatus extends Model
{
    /** @use HasFactory<\Database\Factories\RepCountryStatusFactory> */
    use HasFactory;

    use SoftDeletes;

    protected $table = 'rep_country_status';

    protected $fillable = [
        'representing_country_id',
        'status_name',
        'notes',
        'custom_name',
        'order',
        'is_active',
    ];

    public function representingCountry(): BelongsTo
    {
        return $this->belongsTo(RepresentingCountry::class);
    }

    public function subStatuses(): HasMany
    {
        return $this->hasMany(SubStatus::class)->orderBy('order');
    }

    public function applicationProcess(): ?ApplicationProcess
    {
        return ApplicationProcess::where('name', $this->status_name)->first();
    }

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }
}
