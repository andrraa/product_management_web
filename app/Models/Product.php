<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    public const TYPE_BILLING = 'billing';
    public const TYPE_FOOD = 'food';
    
    use SoftDeletes;

    protected $table = "tbl_products";
    protected $primaryKey = "product_id";
    protected $fillable = [
        'name',
        'price',
        'stock',
        'type'
    ];
}
