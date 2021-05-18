<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Customer extends Authenticatable
{
    use Notifiable;
    protected $table = "customers";

    protected $fillable = [
        'name', 'gender', 'email', 'mobile', 'otp', 'dob', 'latitude', 'longitude', 'state', 'city', 'address', 'pin', 'password', 'status', 'api_token', 'firsbase_token','referral_id'
    ];

    protected $hidden = [
        'password',
    ];

    public function bankAccount()
    {
        return $this->hasMany('App\Models\UserBankAccount', 'user_id', 'id');
    }

    public function wallet()
    {
        return $this->hasOne('App\Models\Wallet', 'user_id', 'id');
    }

    public function address()
    {
        return $this->hasMany('App\Models\Address', 'user_id', 'id');
    }
}
