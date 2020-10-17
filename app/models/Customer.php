<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
class Customer extends Authenticatable
{
    use Notifiable;
    protected $table="customers";

    protected $fillable = [
        'name', 'gender', 'email','mobile','dob', 'latitude', 'latitude', 'state','city','address','pin','password','status','api_token'
    ];

    protected $hidden = [
        'password',
    ];
}
