<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

final class Course extends Model
{
    /** @use HasFactory<\Database\Factories\CourseFactory> */
    use HasFactory;

    use HasUlids;

    protected $fillable = [
        'institution_id',
        'name',
        'code',
        'description',
        'level',
        'subject_area',
        'specialization',
        'duration_value',
        'duration_unit',
        'tuition_fee',
        'fee_currency',
        'fee_period',
        'scholarships',
        'intakes',
        'entry_requirements',
        'english_requirement',
        'other_requirements',
        'mode_of_study',
        'career_outcomes',
        'course_structure',
        'is_active',
        'is_featured',
    ];

    public function institution(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }

    protected function casts(): array
    {
        return [
            'tuition_fee' => 'decimal:2',
            'scholarships' => 'array',
            'intakes' => 'array',
            'other_requirements' => 'array',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
        ];
    }
}
