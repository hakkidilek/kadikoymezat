<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AuctionGallery extends Model
{
    protected $table = "auction_gallery";
    protected $fillable = ['auctionid', 'image'];
    public $timestamps = false;
}
