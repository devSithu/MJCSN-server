<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PointMaster extends Model
{
    protected $table = 'point_master';
    protected $fillable = [
        'point_id', 'action', 'point', 'start_at', 'expiration_at', 'created_by', 'updated_by',
    ];
    protected $primaryKey = 'point_id';
    public $timestamps = true;
}
