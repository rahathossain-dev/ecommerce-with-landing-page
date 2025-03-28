<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class LandingPageFaq extends Model
{
    protected $fillable = [
        'page_id',
        'question',
        'answare'
    ];
}
