<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class RegisterUser extends Authenticatable
{
    protected $table = 'register_users';
    protected $fillable = [
        'user_id', 'name', 'email', 'password', 'creaded_at', 'updated_at',
    ];
    protected $primaryKey = 'user_id';
    public $timestamps = true;
}
