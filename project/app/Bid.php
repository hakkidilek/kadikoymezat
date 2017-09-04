<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bid extends Model
{
    protected $fillable = ['bidder', 'auctionid', 'bid_amount', 'created_at', 'updated_at', 'winner', 'status'];

    public function getBidderAttribute($bidder)
    {
        return UserProfile::findOrFail($bidder);
    }

    public function getAuctionidAttribute($auctionid)
    {
        return Auction::findOrFail($auctionid);
    }

    public static function Bidder($bidder)
    {
        $user = UserProfile::findOrFail($bidder);

        return $user;
    }

    public static function maxBid($auctionid)
    {
        $max = "$".Bid::where('auctionid',$auctionid)->max('bid_amount');
        if ($max == "$"){
            $max = "N/A";
        }
        return $max;
    }

    public static function countBid($auctionid)
    {
        $count = Bid::where('auctionid',$auctionid)->count();
        return $count;
    }

}
