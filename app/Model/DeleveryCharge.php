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

    public function charges()
    {
        return $this->hasMany(DeleveryCharge::class, 'id', 'zone_id');
    }
}
