<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserBankAccount extends Model
{
    protected $table = 'user_bank_accounts';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id','bank_name','ac_no','ifsc','branch_name',
    ];

}
