<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

final class Lead extends Model
{
    /** @use HasFactory<\Database\Factories\LeadFactory> */
    use HasFactory;

    use HasUlids;
    use SoftDeletes;

    protected $fillable = [
        'branch_id',
        'assigned_to',
        'first_name',
        'last_name',
        'email',
        'phone',
        'alternate_phone',
        'date_of_birth',
        'nationality',
        'address',
        'city',
        'state',
        'country',
        'preferred_countries',
        'preferred_level',
        'preferred_subjects',
        'status',
        'source',
        'utm_parameters',
        'notes',
        'lost_reason',
        'last_contact_at',
        'next_follow_up_at',
    ];

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'preferred_countries' => 'array',
            'preferred_subjects' => 'array',
            'utm_parameters' => 'array',
            'last_contact_at' => 'datetime',
            'next_follow_up_at' => 'datetime',
        ];
    }
}
