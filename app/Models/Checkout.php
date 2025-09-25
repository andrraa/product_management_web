<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'fk_user_id', 'user_id');
    }
}
