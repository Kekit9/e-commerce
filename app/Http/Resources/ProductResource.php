<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    private int $id;
    private string $name;
    private string $description;
    private int $maker_id;
    private float $price;
    private string $category;

    /**
     * Constructs a new instance from a resource object
     *
     * @param Product $resource
     */
    public function __construct($resource)
    {
        parent::__construct($resource);

        $this->id = (int)$resource->id;
        $this->name = (string)$resource->name;
        $this->description = (string)$resource->description;
        $this->price = (float)$resource->price;
        $this->maker_id = (int)$resource->maker_id;
        $this->category = (string)$resource->category;
    }

    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'maker_id' => $this->maker_id,
            'category' => $this->category,
        ];
    }
}
