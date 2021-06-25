<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $fillable = [
        'id',
        'customer_id',
        'customer_name',
        'order_datetime',
        'subtotal_price',
        'subtotal_sales_tax',
        'discount',
        'shipping',
        'total_price',
        'total_sales_tax',
    ];
}
