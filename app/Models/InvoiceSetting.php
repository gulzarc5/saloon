<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceSetting extends Model
{
    protected $table = 'invoice_setting';
    protected $primaryKey = 'id';
    protected $fillable = [
        'address','phone','gst','email','note1','note2','note3','image'
    ];
}
