<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Introducer extends Model
{
    protected $table = 'introducer';
    protected $fillable = [
        'introduce_id',
        'user_number',
        'introduced_number',
        'charge_code',
        'status',
        'created_at',
        'updated_at',
    ];
    protected $primaryKey = 'introduce_id';
    public $timestamps = true;
}
