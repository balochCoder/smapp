<?php

declare(strict_types=1);

namespace App\Http\Requests\ApplicationProcesses;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class UpdateApplicationProcessRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:65535'],
            'order' => ['sometimes', 'integer', 'min:0'],
            'is_active' => ['boolean'],
            'representing_country_ids' => ['nullable', 'array'],
            'representing_country_ids.*' => [Rule::exists('representing_countries', 'id')],
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
            'name.max' => 'Process name cannot exceed 255 characters.',
            'order.min' => 'Order must be at least 0.',
            'representing_country_ids.*.exists' => 'One or more selected countries do not exist.',
        ];
    }
}
