<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class LandingPage extends Model
{
    protected $fillable = [
        'name',
        'slug'
    ];
}
