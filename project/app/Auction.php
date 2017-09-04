<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Auction extends Model
{
    protected $fillable = ['createdby', 'category', 'title', 'description', 'owner', 'condition', 'feature_image', 'condition', 'winner', 'paid_status', 'start_date', 'end_date', 'price', 'start_amount', 'featured', 'admin_aproved', 'created_at', 'updated_at', 'status'];


    public function getCreatedbyAttribute($createdby)
    {
        if($createdby == 0){
            return User::where('id',1)->first();
        }
        return UserProfile::where('id',$createdby)->first();
    }

    public static function auctionPending()
    {
        $pending = Auction::where('status','pending')
            ->where('admin_aproved','no')
            ->count();
        return $pending;
    }
    
    public static function closeAuction()
    {
        $auctions = Auction::where('status','open')
            ->where('admin_aproved','yes')
            ->get();

        foreach ($auctions as $auction){

            if ($auction->end_date < date('Y-m-d')){
                $expire = Auction::findOrFail($auction->id);

                $winner = Bid::where('auctionid',$auction->id)->orderBy('bid_amount','desc')->first();
                $win['winner'] = "yes";
                $winner->update($win);

                $data['winner'] = $winner->id;
                $data['status'] = 'closed';
                $expire->update($data);
            }
        }
    }
}
