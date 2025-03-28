<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class DeleveryCharge extends Model
{
    protected $fillable = [
        'zone_id',
        'name',
        'charge'
    ];
}
