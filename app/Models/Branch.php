<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

final class Branch extends Model
{
    /** @use HasFactory<\Database\Factories\BranchFactory> */
    use HasFactory;

    use HasUlids;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'address',
        'city',
        'state',
        'country',
        'postal_code',
        'phone',
        'email',
        'currency',
        'timezone',
        'working_hours',
        'representing_countries',
        'territories',
        'is_active',
        'is_main',
    ];

    protected function casts(): array
    {
        return [
            'working_hours' => 'array',
            'representing_countries' => 'array',
            'territories' => 'array',
            'is_active' => 'boolean',
            'is_main' => 'boolean',
        ];
    }
}
