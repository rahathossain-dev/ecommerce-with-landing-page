<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class LandingPageOrder extends Model
{
    protected $fillable = [
        'name',
        'number',
        'address',
        'comment',
        'product_id',
        'shipping_method',
        'quantity'
    ];
    public function product()
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }

    public function deliveryCharge()
    {
        return $this->hasOne(DeleveryCharge::class, 'id', 'shipping_method');
    }
}
