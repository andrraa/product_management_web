<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{
    use SoftDeletes;

    protected $table = 'tbl_bookings';

    protected $primaryKey = 'booking_id';

    protected $fillable = [
        'customer_name',
        'customer_phone',
        'payment_method',
        'package_name',
        'package_price',
        'package_quantity',
        'total',
        'by_user_id',
        'by_user_name',
        'notes',
        'is_paid',
        'is_completed',
    ];
}
