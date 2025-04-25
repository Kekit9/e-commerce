<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ServiceListRequest extends FormRequest
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
     * @return array<string, string|array<string, string>>
     */
    public function rules(): array
    {
        return [
            'per_page' => 'nullable|integer|min:10',
            'sort_by' => 'nullable|string',
            'sort_direction' => 'nullable|in:asc,desc',
            'service_type' => 'nullable|string'
        ];
    }
}
