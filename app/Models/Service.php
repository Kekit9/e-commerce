<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Service extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'services';

    protected $fillable = [
        'service_type',
        'duration',
        'price',
        'terms',
    ];

    /**
     * Get all of the products for the service through the intermediate table.
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_service')
            ->withTimestamps();
    }
}
