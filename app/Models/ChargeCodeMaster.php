<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChargeCodeMaster extends Model
{
    protected $table = 'chargecode_master';
    protected $fillable = [
        'chargecode_id', 'career', 'chaegecode', 'status', 'created_by', 'updated_by', 'created_at', 'updated_at',
    ];
    protected $primaryKey = 'chargecode_id';
    public $timestamps = true;
}
