<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

class ServiceResource extends JsonResource
{
    private int $id;
    private string $service_type;
    private int $duration;
    private float $price;
    private string $terms;

    /**
     * Constructs a new instance from a resource object
     *
     * @param object $resource
     */
    public function __construct($resource)
    {
        parent::__construct($resource);

        $this->id = (int)$resource->id;
        $this->service_type = (string)$resource->service_type;
        $this->duration = (int)$resource->duration;
        $this->price = (float)$resource->price;
        $this->terms = (string)$resource->terms;
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
            'service_type' => $this->service_type,
            'duration' => $this->duration,
            'price' => $this->price,
            'terms' => $this->terms,
        ];
    }
}
