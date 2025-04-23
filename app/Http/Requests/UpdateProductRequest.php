<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'maker_id' => 'required|integer|exists:makers,id',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'category' => 'required|string',
        ];
    }
}
