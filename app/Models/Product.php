<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'products';

    protected $fillable = [
        'name',
        'description',
        'price',
        'category',
        'ordered_at',
        'maker_id',
    ];

    /**
     * Get all of the services for the product through the intermediate table.
     */
    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class, 'product_service')
            ->withTimestamps();
    }

    /**
     * Set relationship between product and its manufacturer.
     */
    public function maker(): BelongsTo
    {
        return $this->belongsTo(Maker::class);
    }
}
