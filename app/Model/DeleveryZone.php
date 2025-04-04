<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class DeleveryZone extends Model
{
    protected $fillable = [
        'name'
    ];

    public function deleveryCharge()
    {
        return $this->hasOne(DeleveryCharge::class, 'zone_id', 'id');
    }

}
