<?php

namespace App\Http\Resources;

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

    public function __construct($resource)
    {
        parent::__construct($resource);

        $this->id = $resource->id;
        $this->name = $resource->name;
        $this->description = $resource->description;
        $this->price = $resource->price;
        $this->maker_id = $resource->maker_id;
        $this->category = $resource->category;
    }

    /**
     * Transform the resource into an array.
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
