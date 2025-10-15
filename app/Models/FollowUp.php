<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

final class FollowUp extends Model
{
    /** @use HasFactory<\Database\Factories\FollowUpFactory> */
    use HasFactory;

    use HasUlids;

    protected $fillable = [
        'lead_id',
        'student_id',
        'application_id',
        'created_by',
        'assigned_to',
        'type',
        'subject',
        'notes',
        'follow_up_date',
        'follow_up_time',
        'status',
        'outcome',
        'completed_at',
        'completed_by',
        'reminder_sent',
        'reminder_sent_at',
    ];

    protected function casts(): array
    {
        return [
            'follow_up_date' => 'date',
            'completed_at' => 'datetime',
            'reminder_sent' => 'boolean',
            'reminder_sent_at' => 'datetime',
        ];
    }
}
