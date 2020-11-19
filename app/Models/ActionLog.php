<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActionLog extends Model
{
    protected $table = 'action_log';
    protected $fillable = [
        'action_id', 'user_number', 'action', 'action_at', 'parameter', 'point', 'os', 'os_version', 'app', 'app_version',
    ];
    protected $primaryKey = 'action_id';
    public $timestamps = true;
}
