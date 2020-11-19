<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FirebaseToken extends Model
{
    protected $table = 'firebase_tokens';
    protected $fillable = [
        'login_id', 'fcm_token',
    ];
    protected $primaryKey = 'firebase_id';
}
