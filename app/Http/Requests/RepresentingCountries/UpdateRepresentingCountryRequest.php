<?php

declare(strict_types=1);

namespace App\Http\Requests\RepresentingCountries;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class UpdateRepresentingCountryRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'monthly_living_cost' => ['nullable', 'numeric', 'min:0', 'max:999999.99'],
            'currency' => ['nullable', 'string', 'size:3'],
            'visa_requirements' => ['nullable', 'string', 'max:65535'],
            'part_time_work_details' => ['nullable', 'string', 'max:65535'],
            'country_benefits' => ['nullable', 'string', 'max:65535'],
            'is_active' => ['boolean'],
            'application_process_ids' => ['nullable', 'array'],
            'application_process_ids.*' => [Rule::exists('application_processes', 'id')],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'monthly_living_cost.numeric' => 'Monthly living cost must be a valid number.',
            'monthly_living_cost.min' => 'Monthly living cost cannot be negative.',
            'application_process_ids.*.exists' => 'One or more selected application processes do not exist.',
        ];
    }
}
