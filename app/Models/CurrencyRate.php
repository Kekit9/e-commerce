<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CurrencyRate extends Model
{
    protected $fillable = [
        'currency_iso',
        'currency_code',
        'buy_rate',
        'sale_rate',
        'last_updated'
    ];

    protected $casts = [
        'buy_rate' => 'decimal:6',
        'sale_rate' => 'decimal:6',
        'last_updated' => 'datetime'
    ];
}
