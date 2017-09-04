<?php

namespace App\Http\Controllers;

use App\Auction;
use App\AuctionGallery;
use App\Bid;
use App\Category;
use App\Country;
use App\Donation;
use App\SectionTitles;
use App\Settings;
use App\UserProfile;
use App\Withdraw;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

class AuctionController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $language = SectionTitles::findOrFail(1);
        $auctions = Auction::orderBy('id','desc')->get();
        return view('admin.auctionlist',compact('auctions','language'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.auctionadd',compact('categories'));
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
        $data->fill($request->all());

        if ($file = $request->file('photo')){
            $photo_name = time().$request->file('photo')->getClientOriginalName();
            $file->move('assets/images/auction',$photo_name);
            $data['feature_image'] = $photo_name;
        }
        $data['admin_aproved'] = 'yes';
        $data['status'] = 'open';
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

        return redirect('admin/auction')->with('message','New Auction Created Successfully.');
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
        $bids = Bid::where('auctionid',$id)->orderBy('updated_at','desc')->get();
        return view('admin.auctiondetails',compact('auction','bids'));
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
        return view('admin.auctionedit',compact('auction','categories'));
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
        $auction = Auction::findOrFail($id);
        $data = $request->all();
        if ($file = $request->file('photo')){
            $photo_name = time().$request->file('photo')->getClientOriginalName();
            $file->move('assets/images/auction',$photo_name);
            $data['feature_image'] = $photo_name;
        }

        if ($request->galdel == 1){
            $gal = AuctionGallery::where('auctionid',$id);
            $gal->delete();
        }

        if ($request->featured == 1){
            $data['featured'] = 1;
        }else{
            $data['featured'] = 0;
        }

        $auction->update($data);

        if ($files = $request->file('gallery')){
            foreach ($files as $file){
                $gallery = new AuctionGallery;
                $image_name = str_random(2).time().$file->getClientOriginalName();
                $file->move('assets/images/gallery',$image_name);
                $gallery['image'] = $image_name;
                $gallery['auctionid'] = $id;
                $gallery->save();
            }
        }

        return redirect('admin/auction')->with('message','Auction Updated Successfully.');;
    }

    public function titles(Request $request)
    {
        $service = SectionTitles::findOrFail(1);
        $data['pricing_title'] = $request->pricing_title;
        $data['pricing_text'] = $request->pricing_text;
        $data['newcamp_title'] = $request->newcamp_title;
        $data['newcamp_text'] = $request->newcamp_text;
        $service->update($data);
        return redirect('admin/auction')->with('message','Auction Section Title & Text Updated Successfully.');
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
        unlink('assets/images/auction/'.$auction->feature_image);
        $auction->delete();

        return redirect('admin/auction')->with('message','Auction Deleted Successfully.');
    }


    public function pending()
    {
        $auctions = Auction::where('status','pending')
            ->where('admin_aproved','no')
            ->get();
        return view('admin.auctionpending',compact('auctions'));
    }

    public function pendingview($id)
    {
        $auction = Auction::findOrFail($id);
        return view('admin.viewpending',compact('auction'));
    }


    public function makeWinner($bid,$auction)
    {
        $winauction = Auction::findOrFail($auction);
        $winbid = Bid::findOrFail($bid);

        $message = "Your Bid has Won the Auction for <b>".$winauction->title."</b>\n Please Login to your Account and Pay your Bid Amount.";
        mail($winbid->bidder->email,"Your Bid Has Won",$message);

        $datas['winner'] = "yes";
        $winbid->update($datas);

        $data['winner'] = $bid;
        $data['end_date'] = date('Y-m-d H:i:s');
        $data['status'] = "closed";
        $winauction->update($data);

        //return redirect('admin/auction')->with('message',$message);
        return redirect('admin/auction')->with('message','Auction Winner Selected Successfully.');
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
        return redirect('admin/auction/'.$auction)->with('message','Auction Winner Removed Successfully.');
    }

    public function emailbidder($bid)
    {
        $bidder = Bid::findOrFail($bid);

        return view('admin.bidemail',compact('bidder'));
    }

    public function sendemail(Request $request,$bid)
    {
        $bidder = Bid::findOrFail($bid);
        $subject = $request->subject;
        $message = $request->message;
        mail($bidder->bidder->email,$subject,$message);
        return view('admin.bidemail',compact('bidder'))->with('message','Your Email Send Successfully.');
    }

    public function accept($id)
    {
        $auction = Auction::findOrFail($id);

        $message = "Your Submitted auction Has Been Accepted Successfully.";
        mail($auction->createdby->email,"Your Auction Has Been Accepted",$message);

        $data['admin_aproved'] = "yes";
        $data['status'] = "open";
        $auction->update($data);

        return redirect('admin/auction/pending')->with('message','Auction Approved Successfully.');
    }

    public function reject($id)
    {
        $auction = Auction::findOrFail($id);

        $reason = Input::get('reason');
        $message = "Reason: ".$reason;
        mail($auction->createdby->email,"Your Auction Has Been Rejected",$message);

        $data['admin_aproved'] = "no";
        $data['status'] = "reject";
        $auction->update($data);

        return redirect('admin/auction/pending')->with('message','Auction Rejected Successfully.');
    }

    public function hardreject($id)
    {
        $auction = Auction::findOrFail($id);

        $reason = Input::get('reason');
        $message = "Reason: ".$reason;
        mail($auction->createdby->email,"Your auction Has Been Rejected",$message);

        $auction->delete();
        return redirect('admin/auction/pending')->with('message','auction Rejected & Deleted Successfully.');
    }

    public function close($id)
    {
        $auction = Auction::findOrFail($id);
        $data['status'] = "closed";
        $auction->update($data);

        return redirect('admin/auction')->with('message','Auction Closed Successfully.');
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

        return redirect('admin/auction')->with('message','Auction Opened Successfully.');
    }

    public function withdraw($id)
    {
        $auction = Auction::findOrFail($id);
        $countries = Country::all();
        return view('admin.withdrawfund',compact('auction','countries'));
    }

    public function withdraws(Request $request,$id)
    {
        $from = UserProfile::findOrFail(Auth::user()->id);

        $auction = auction::findOrFail($id);

        $withdrawcharge = Settings::findOrFail(1);
        $wcharge = $withdrawcharge->withdraw_charge;

        if($request->amount > 0){

            $charge = ($wcharge / 100) * $request->amount;
            $charge = number_format((float)$charge,2,'.','');

            $amount = $request->amount - $charge;
            $amount = number_format((float)$amount,2,'.','');

            if ($auction->available_fund >= $request->amount){

                $balance1['available_fund'] = $auction->available_fund - $request->amount;
                $auction->update($balance1);

                $newwithdraw = new Withdraw();
                $newwithdraw['campid'] = $id;
                $newwithdraw['method'] = $request->methods;
                $newwithdraw['acc_email'] = $request->acc_email;
//                $newwithdraw['acc_phone'] = $request->acc_phone;
                $newwithdraw['iban'] = $request->iban;
                $newwithdraw['country'] = $request->acc_country;
                $newwithdraw['acc_name'] = $request->acc_name;
                $newwithdraw['address'] = $request->address;
                $newwithdraw['swift'] = $request->swift;
//                $newwithdraw['reference'] = $request->reference;
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
        $auction = auction::findOrFail($id);
        unlink('assets/images/auction/'.$auction->feature_image);
        $auction->delete();

        return redirect('admin/auction')->with('message','Auction Deleted Successfully.');
    }
}
