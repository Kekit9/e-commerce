<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateServiceRequest extends FormRequest
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
     * @return array<string, string|array<string, string>|ValidationRule>
     */
    public function rules(): array
    {
        return [
            'service_type' => 'required|string|max:255',
            'duration' => 'required|integer|max:12',
            'price' => 'required|numeric',
            'terms' => 'required|string|max:255',
        ];
    }
}
