<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

final class OrganizationRegistrationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Public registration allowed
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Organization Information
            'organization_name' => ['required', 'string', 'max:255'],
            'organization_slug' => ['required', 'string', 'max:255', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/', 'unique:organizations,slug'],
            'organization_email' => ['required', 'string', 'email', 'max:255'],
            'organization_phone' => ['nullable', 'string', 'max:50'],

            // Admin User Information
            'admin_name' => ['required', 'string', 'max:255'],
            'admin_email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'admin_password' => ['required', 'string', 'confirmed', Password::defaults()],

            // Subscription Plan
            'subscription_plan' => ['required', 'string', 'in:trial,basic,premium,enterprise'],

            // Terms & Conditions
            'terms_accepted' => ['required', 'accepted'],
        ];
    }

    /**
     * Get custom error messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'organization_slug.regex' => 'The organization slug must be lowercase letters and numbers separated by hyphens (e.g., my-company).',
            'organization_slug.unique' => 'This organization slug is already taken. Please choose another.',
            'admin_email.unique' => 'This email is already registered.',
            'terms_accepted.accepted' => 'You must accept the terms and conditions to continue.',
        ];
    }

    /**
     * Get custom attribute names.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'organization_name' => 'organization name',
            'organization_slug' => 'organization slug',
            'organization_email' => 'organization email',
            'organization_phone' => 'organization phone',
            'admin_name' => 'admin name',
            'admin_email' => 'admin email',
            'admin_password' => 'admin password',
            'subscription_plan' => 'subscription plan',
            'terms_accepted' => 'terms and conditions',
        ];
    }
}
