<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

final class SubStatus extends Model
{
    /** @use HasFactory<\Database\Factories\SubStatusFactory> */
    use HasFactory;

    use SoftDeletes;

    protected $fillable = [
        'rep_country_status_id',
        'name',
        'description',
        'order',
        'is_active',
    ];

    public function repCountryStatus(): BelongsTo
    {
        return $this->belongsTo(RepCountryStatus::class);
    }

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }
}
