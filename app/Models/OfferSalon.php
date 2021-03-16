<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OfferSalon extends Model
{
    protected $table = 'offer_salons';
    protected $fillable = [
        'offer_id','client_id'
    ];

    public function salon()
    {
        return $this->belongsTo('App\Models\Client','client_id','id');
    }
    
}
