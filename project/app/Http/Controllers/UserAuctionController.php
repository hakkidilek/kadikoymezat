<?php

namespace App\Http\Controllers;

use App\AuctionGallery;
use App\Bid;
use Illuminate\Http\Request;
use App\Auction;
use App\Category;
use App\Country;
use App\Settings;
use App\UserProfile;
use App\Withdraw;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
class UserAuctionController extends Controller
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
        $auctions = Auction::where('createdby',Auth::user()->id)->orderBy('id','desc')->get();
        return view('user.auctionlist',compact('auctions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all();
        return view('user.auctionadd',compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = new Auction;

        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255',
            'photo' => 'mimes:jpeg,bmp,png',
            'price' => 'numeric|required',
        ]);

        if ($validator->fails()) {
            return redirect('user/auction/create')
                ->withErrors($validator)
                ->withInput();
        }else{

            $data->fill($request->all());

            if ($file = $request->file('photo')){
                $photo_name = time().$request->file('photo')->getClientOriginalName();
                $file->move('assets/images/auction',$photo_name);
                $data['feature_image'] = $photo_name;
            }
            $data['owner'] = 'user';
            $data['createdby'] = Auth::user()->id;
            $data->save();
            $lastid = $data->id;

            if ($files = $request->file('gallery')){
                foreach ($files as $file){
                    $gallery = new AuctionGallery();
                    $image_name = str_random(2).time().$file->getClientOriginalName();
                    $file->move('assets/images/gallery',$image_name);
                    $gallery['image'] = $image_name;
                    $gallery['auctionid'] = $lastid;
                    $gallery->save();
                }
            }

            return redirect('user/auction/'.$lastid.'/publish')->with('message','New Auction Created Successfully.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $auction = Auction::findOrFail($id);
        if ($auction->createdby->id == Auth::user()->id){
            $bids = Bid::where('auctionid',$id)->orderBy('updated_at','desc')->get();
            return view('user.auctiondetails',compact('auction','bids'));
        }else{
            return redirect('user/auction')->with('error','Sorry, You are not the Owner of this Auction.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $categories = Category::all();
        $auction = Auction::findOrFail($id);

        if ($auction->createdby->id == Auth::user()->id){
            return view('user.auctionedit',compact('auction','categories'));
        }else{
            return redirect('user/auction')->with('error','Sorry, You are not the Owner of this Auction.');
        }
    }

    public function publish($id)
    {
        $auction = Auction::findOrFail($id);
        return view('user.publish',compact('auction'));
    }

    public function publishnow(Request $request)
    {
        $auction = Auction::findOrFail($request->auction);
        $data = array();
        if ($request->amount == 0){
            $data['admin_aproved'] = "yes";
            $data['status'] = "open";
            if ($request->featured == "yes"){
                $data['featured'] = 1;
            }
        }
        $auction->update($data);
        return redirect('user/auction')->with('message','Your Auction Published Successfully');
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
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255',
            'photo' => 'mimes:jpeg,bmp,png',
            'goal' => 'numeric|required',
        ]);

        if ($validator->fails()) {
            return redirect('user/auction/'.$id.'/edit')
                ->withErrors($validator)
                ->withInput();
        }else {
            $auction = Auction::findOrFail($id);
            $data = $request->all();
            if ($file = $request->file('photo')) {
                $photo_name = time() . $request->file('photo')->getClientOriginalName();
                $file->move('assets/images/auction', $photo_name);
                $data['feature_image'] = $photo_name;
            }

            if($auction->status == "reject"){
                $data['status'] = "pending";
            }

            $auction->update($data);

            return redirect('user/auction')->with('message', 'Auction Updated Successfully.');;

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
        $auction = Auction::findOrFail($id);

        if ($auction->createdby == Auth::user()->id){
            unlink('assets/images/auction/'.$auction->feature_image);
            $auction->delete();
            return redirect('user/auction')->with('message','Auction Deleted Successfully.');
        }else{
            return redirect('user/auction')->with('error','Sorry, You are not the Owner of this Auction.');
        }
    }


    public function makeWinner($bid,$auction)
    {
        $winauction = Auction::findOrFail($auction);
        $winbid = Bid::findOrFail($bid);

        $message = "Your Bid has Won the Auction for ".$winauction->title."\n Please Login to your Account and Pay your Bid Amount.";
        mail($winbid->bidder->email,"Your Bid Has Won",$message);

        $datas['winner'] = "yes";
        $winbid->update($datas);

        $data['winner'] = $bid;
        $data['end_date'] = date('Y-m-d H:i:s');
        $data['status'] = "closed";
        $winauction->update($data);

        //return redirect('admin/auction')->with('message',$message);
        return redirect('user/auction')->with('message','Auction Winner Selected Successfully.');
    }

    public function cancelWinner($auction)
    {
        $winauction = Auction::findOrFail($auction);
        $winbid = Bid::findOrFail($winauction->winner);

        $datas['winner'] = "no";
        $winbid->update($datas);

        $data['winner'] = null;
        $winauction->update($data);

        //return redirect('admin/auction')->with('message',$message);
        return redirect('user/auction/'.$auction)->with('message','Auction Winner Removed Successfully.');
    }

    public function emailbidder($bid)
    {
        $bidder = Bid::findOrFail($bid);

        return view('user.bidemail',compact('bidder'));
    }

    public function sendemail(Request $request,$bid)
    {
        $bidder = Bid::findOrFail($bid);
        $subject = $request->subject;
        $message = $request->message;
        mail($bidder->bidder->email,$subject,$message);
        return view('user.bidemail',compact('bidder'))->with('message','Your Email Send Successfully.');
    }

    public function close($id)
    {
        $auction = Auction::findOrFail($id);
        $data['status'] = "closed";
        $auction->update($data);

        return redirect('user/auction')->with('message','Auction Closed Successfully.');
    }

    public function open($id)
    {
        $auction = Auction::findOrFail($id);
        if ($auction->admin_aproved=="yes"){
            $data['status'] = "open";
        }else{
            $data['status'] = "pending";
        }
        $auction->update($data);

        return redirect('user/auction')->with('message','Auction Opened Successfully.');
    }

    public function withdrawlist()
    {
        $withdraws = Withdraw::where('userid',Auth::user()->id)->get();
        return view('user.withdraws',compact('withdraws'));
    }

    public function withdraw()
    {
        $countries = Country::all();
        return view('user.withdrawfund',compact('countries'));
    }

    public function withdraws(Request $request)
    {
        $from = UserProfile::findOrFail(Auth::user()->id);

            $withdrawcharge = Settings::findOrFail(1);
            $wcharge = $withdrawcharge->withdraw_charge;

            if($request->amount > 0){

                $charge = ($wcharge / 100) * $request->amount;
                $charge = number_format((float)$charge,2,'.','');

                $amount = $request->amount - $charge;
                $amount = number_format((float)$amount,2,'.','');

                if ($from->acc_balance >= $request->amount){

                    $balance1['acc_balance'] = $from->acc_balance - $request->amount;
                    $from->update($balance1);

                    $newwithdraw = new Withdraw();
                    $newwithdraw['userid'] = Auth::user()->id;
                    $newwithdraw['method'] = $request->methods;
                    $newwithdraw['acc_email'] = $request->acc_email;
                    $newwithdraw['iban'] = $request->iban;
                    $newwithdraw['country'] = $request->acc_country;
                    $newwithdraw['acc_name'] = $request->acc_name;
                    $newwithdraw['address'] = $request->address;
                    $newwithdraw['swift'] = $request->swift;
                    $newwithdraw['amount'] = $amount;
                    $newwithdraw['fee'] = $charge;
                    $newwithdraw->save();

                    return redirect()->back()->with('message','Withdraw Request Sent Successfully.');

                }else{
                    return redirect()->back()->with('error','Insufficient Balance.')->withInput();
                }
            }
            return redirect()->back()->with('error','Please enter a valid amount.')->withInput();

    }

    public function delete($id)
    {
        Withdraw::where('campid',$id)->delete();
        Donation::where('campid',$id)->delete();
        $auction = Auction::findOrFail($id);
        if ($auction->createdby->id == Auth::user()->id){

            unlink('assets/images/auction/'.$auction->feature_image);
            $auction->delete();

            return redirect('user/auction')->with('message','Auction Deleted Successfully.');
        }else{
            return redirect('user/auction')->with('error','Sorry, You are not the Owner of this Auction.');
        }
    }


}
