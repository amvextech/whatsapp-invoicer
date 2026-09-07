<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'uuid',
        'merchant_name',
        'upi_id',
        'client_name',
        'client_phone',
        'items',
        'grand_total'
    ];

    protected $casts = [
        'items' => 'array',
    ];
}