<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

final class Country extends Model
{
    /** @use HasFactory<\Database\Factories\CountryFactory> */
    use HasFactory;

    use HasUlids;

    protected $fillable = [
        'name',
        'code',
        'region',
        'flag',
        'application_process_info',
        'visa_types',
        'required_documents',
        'application_stages',
        'is_active',
    ];

    public function institutions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Institution::class);
    }

    protected function casts(): array
    {
        return [
            'visa_types' => 'array',
            'required_documents' => 'array',
            'application_stages' => 'array',
            'is_active' => 'boolean',
        ];
    }
}
