<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rules\Unique;

class CreateServiceRequest extends FormRequest
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
     * @return array<string, array<int, Unique|string>|string>
     */
    public function rules(): array
    {
        return [
            'service_type' => [
                'required',
                'string',
                'max:255',
                Rule::unique('services')->where(function ($query) {
                    return $query
                        ->where('service_type', $this->input('service_type'))
                        ->where('duration', $this->input('duration'))
                        ->where('price', $this->input('price'));
                })
            ],
            'duration' => 'required|numeric',
            'price' => 'required|numeric',
            'terms' => 'required|string|max:255',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ])
        );
    }
}
