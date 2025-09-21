<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Checkout extends Model
{
    protected $table = 'tbl_checkouts';
    protected $primaryKey = 'checkout_id';
    protected $fillable = [
        'name',
        'quantity',
        'payment_method',
        'type',
        'price',
        'total_price',
        'fk_user_id'
    ];
}
