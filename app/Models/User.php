<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    public const ROLE_ADMIN = 'admin';
    public const ROLE_EMPLOYEE = 'employee';

    public const SHIFT_MORNING = 'morning';
    public const SHIFT_NIGHT = 'night';

    use SoftDeletes;
    
    protected $table = 'tbl_users';
    protected $primaryKey = 'user_id';
    protected $fillable = [
        'name',
        'username',
        'password',
        'shift',
        'role'
    ];
}
