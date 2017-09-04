<?php

namespace App\Http\Controllers;

use App\Auction;
use App\Bid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserBidController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:profile');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $bids = Bid::where('bidder',Auth::user()->id)->orderBy('updated_at','desc')->get();
        return view('user.mybids',compact('bids'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $bid = Bid::findOrFail($id);
        if (Auction::where('id',$bid->auctionid->id)->count() > 0){
            $auction = Auction::findOrFail($bid->auctionid->id);
        }else{
            $auction = new \stdClass();
            $auction->id = "javascript:;";
            $auction->title = "Auction Deleted";
            $auction->status = "Auction Deleted";
            $auction->created_at = "Auction Deleted";
            $auction->price = "Auction Deleted";
        }
        return view('user.editmybid',compact('bid','auction'));
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function winnerpay($id)
    {
        $bid = Bid::findOrFail($id);
        if (Auction::where('id',$bid->auctionid->id)->count() > 0){

            if (Auction::findOrFail($bid->auctionid->id)->paid_status == "yes"){
                return redirect('user/mybids')->with('error','This Payment is Already Paid');

            }else if (Auction::findOrFail($bid->auctionid->id)->winner != $id){
                return redirect('user/mybids')->with('error','You are not the winner of this Auction.');
            }
            $auction = Auction::findOrFail($bid->auctionid->id);
        }else{
            $auction = new \stdClass();
            $auction->id = "javascript:;";
            $auction->title = "Auction Deleted";
            $auction->status = "Auction Deleted";
            $auction->created_at = "Auction Deleted";
            $auction->price = "Auction Deleted";
        }
        return view('user.winnerpay',compact('bid','auction'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
*/
    public function update(Request $request, $id)
    {
        $bid = Bid::findOrFail($id);
        $highest = Bid::where('auctionid',$bid->auctionid->id)->max('bid_amount');
        if ($highest < $request->bid_amount){

            $data['bid_amount'] = $request->bid_amount;
            $bid->update($data);
            return redirect('user/mybids')->with('message','Your Bid Updated Successfully.');
        }else{
            return redirect()->back()->with('error','Your Bid Must Be Bigger Than $'.$highest.'.');
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
