<?php

declare(strict_types=1);

namespace App\Http\Requests\ApplicationProcesses;

use Illuminate\Foundation\Http\FormRequest;

final class UpdateApplicationProcessRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'color' => ['nullable', 'string', 'in:blue,green,yellow,red,purple,orange,gray'],
            'order' => ['nullable', 'integer', 'min:1'],
        ];
    }
}
