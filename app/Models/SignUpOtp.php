<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SignUpOtp extends Model
{
    protected $table = "sign_up_otp";
    protected $primaryKey = "id";

    protected $fillable = [
        'mobile', 'otp'
    ];
}
