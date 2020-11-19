<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class CommunityUser extends Authenticatable
{
    //enable hasApiToken and notifiable
    use Notifiable, HasApiTokens;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'community_user';
    protected $fillable = [
        'user_number',
        'login_id',
        'password',
        'connect_sns',
        'user_type',
        'user_name',
        'gender',
        'date_of_birth',
        'nrc_number',
        'graduated_from',
        'graduated_dep',
        'graduated_year',
        'region',
        'address',
        'phone_number',
        'email',
        'nrc_image',
        'career',
        'status',
        'answer_one',
        'answer_two',
        'answer_three',
        'answer_four',
        'created_at',
        'updated_at',
    ];
    protected $primaryKey = 'user_number';
    public $timestamps = true;

    public function survey_visitors()
    {
        return $this->hasMany('App\Models\SurveyVisitor', 'user_number', 'user_number');
    }
}
