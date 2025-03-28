<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class LandingPageSection extends Model
{
    protected $fillable = [
        'page_id',
        'title',
        'description',
        'button_text',
        'button_link',
        'banner'
    ];
}
