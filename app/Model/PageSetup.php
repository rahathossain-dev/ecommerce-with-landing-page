<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class PageSetup extends Model
{
    protected $fillable = [
        'title',
        'description',
        'price',
        'offer_time',
        'product_id',
        'delevery_id',
        'landing_page_id'
    ];

    public function sections()
    {
        return $this->hasMany(LandingPageSection::class, 'page_id', 'id');
    }

    public function faqs()
    {
        return $this->hasMany(LandingPageFaq::class, 'page_id', 'id');
    }

    public function customer_reviews()
    {
        return $this->hasMany(LandingPageReview::class, 'page_id', 'id');
    }
}
