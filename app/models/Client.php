<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
class Client extends Authenticatable
{
    protected $table ='clients';
    protected $fillable = [
        'name',
        'mobile',
        'email',
        'otp',
        'work_experience',
        'state',
        'city',
        'address',
        'image',
        'gst',
        'latitude',
        'longitude',
        'opening_time',
        'closing_time',
        'clientType',
        'status',
        'password',
        'profile_status'
    ];

    protected $hidden = ['password'];
}
