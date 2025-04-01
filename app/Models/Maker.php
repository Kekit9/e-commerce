<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Maker extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'makers';

    protected $fillable = [
        'name',
    ];

    /**
     * Set relationship between manufacturer and his product.
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
