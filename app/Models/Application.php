<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

final class Application extends Model
{
    /** @use HasFactory<\Database\Factories\ApplicationFactory> */
    use HasFactory;

    use HasUlids;
    use \Illuminate\Database\Eloquent\SoftDeletes;

    protected $fillable = [
        'application_number',
        'student_id',
        'branch_id',
        'country_id',
        'institution_id',
        'course_id',
        'assigned_officer',
        'intake',
        'intake_date',
        'status',
        'current_stage',
        'workflow_stages',
        'application_date',
        'decision_date',
        'decision_notes',
        'document_checklist',
        'conditional_offer',
        'offer_conditions',
        'offer_letter_path',
        'offer_expiry_date',
        'application_fee',
        'application_fee_paid',
        'tuition_deposit',
        'tuition_deposit_paid',
        'visa_status',
        'visa_application_date',
        'visa_decision_date',
        'notes',
        'internal_notes',
    ];

    protected function casts(): array
    {
        return [
            'intake_date' => 'date',
            'workflow_stages' => 'array',
            'application_date' => 'date',
            'decision_date' => 'date',
            'document_checklist' => 'array',
            'conditional_offer' => 'boolean',
            'offer_conditions' => 'array',
            'offer_expiry_date' => 'date',
            'application_fee' => 'decimal:2',
            'application_fee_paid' => 'boolean',
            'tuition_deposit' => 'decimal:2',
            'tuition_deposit_paid' => 'boolean',
            'visa_application_date' => 'date',
            'visa_decision_date' => 'date',
        ];
    }
}
