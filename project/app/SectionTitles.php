<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SectionTitles extends Model
{
    protected $fillable = ['service_title', 'service_text','category_title','category_text','pricing_title', 'pricing_text','newcamp_title','newcamp_text', 'portfolio_title', 'portfolio_text', 'testimonial_title', 'testimonial_text'];
    public $timestamps = false;
}
