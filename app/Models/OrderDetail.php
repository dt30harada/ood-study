<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'item_id',
        'item_name',
        'item_category_name',
        'unit_price',
        'unit_sales_tax',
        'quantity',
        'order_price',
        'order_sales_tax',
    ];
}
