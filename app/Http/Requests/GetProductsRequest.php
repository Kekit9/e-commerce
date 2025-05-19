<?php

namespace App\Http\Requests;

use App\Repositories\Product\ProductRepository;
use Illuminate\Foundation\Http\FormRequest;

class GetProductsRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'maker_id' => 'nullable|integer',
            'service_id' => 'nullable|integer',
            'sort_by' => 'nullable|string',
            'sort_direction' => 'nullable|in:asc,desc',
            'per_page' => 'nullable|integer|min:10',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'per_page' => $this->per_page ?? ProductRepository::DEFAULT_PER_PAGE,
            'sort_by' => $this->sort_by ?? ProductRepository::DEFAULT_SORT_VALUE,
            'sort_direction' => $this->sort_direction ?? ProductRepository::DEFAULT_SORT_DIRECTION,
        ]);
    }

    /**
     * Get validated data in service-expected format
     *
     * @return array{
     *     maker_id: int|null,
     *     service_id: int|null,
     *     sort_by: string,
     *     sort_direction: 'asc'|'desc',
     *     per_page: int
     * }
     */
    public function validatedForService(): array
    {
        $validated = parent::validated();

        /** @var 'asc'|'desc' $sortDirection */
        $sortDirection = $validated['sort_direction'];

        return [
            'maker_id' => isset($validated['maker_id']) ? (int)$validated['maker_id'] : null,
            'service_id' => isset($validated['service_id']) ? (int)$validated['service_id'] : null,
            'sort_by' => (string)$validated['sort_by'],
            'sort_direction' => $sortDirection,
            'per_page' => (int)$validated['per_page']
        ];
    }
}
