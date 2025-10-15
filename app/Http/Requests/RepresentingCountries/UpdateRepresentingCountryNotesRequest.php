<?php

declare(strict_types=1);

namespace App\Http\Requests\RepresentingCountries;

use Illuminate\Foundation\Http\FormRequest;

final class UpdateRepresentingCountryNotesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'process_descriptions' => ['required', 'array'],
            'process_descriptions.*' => ['nullable', 'string', 'max:65535'],
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
            'process_descriptions.required' => 'Process descriptions are required.',
            'process_descriptions.array' => 'Process descriptions must be an array.',
            'process_descriptions.*.max' => 'Each process description cannot exceed 65535 characters.',
        ];
    }
}