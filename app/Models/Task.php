<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

final class Task extends Model
{
    /** @use HasFactory<\Database\Factories\TaskFactory> */
    use HasFactory;

    use HasUlids;

    protected $fillable = [
        'title',
        'description',
        'created_by',
        'assigned_to',
        'branch_id',
        'application_id',
        'lead_id',
        'student_id',
        'parent_task_id',
        'category',
        'priority',
        'status',
        'due_date',
        'completed_at',
        'completed_by',
        'completion_notes',
    ];

    protected function casts(): array
    {
        return [
            'due_date' => 'date',
            'completed_at' => 'datetime',
        ];
    }
}
