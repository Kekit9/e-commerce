<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CurrencyRate extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'currency_rates';

    /**
     * The attributes that are mass assignable
     *
     * @var array<string>
     */
    protected $fillable = [
        'currency_iso',
        'currency_code',
        'buy_rate',
        'sale_rate',
        'last_updated'
    ];

    /**
     * The attributes that should be cast
     *
     * @var array<string, string>
     */
    protected $casts = [
        'buy_rate' => 'decimal:6',
        'sale_rate' => 'decimal:6',
        'last_updated' => 'datetime'
    ];
}
