<?php

namespace App\Http\Controllers;

use App\Auction;
use App\AuctionGallery;
use App\Bid;
use App\Category;
use App\Gallery;
use App\PageSettings;
use App\Portfolio;
use App\SectionTitles;
use App\ServiceSection;
use App\Settings;
use App\Counter;
use App\Subscribers;
use App\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;

class FrontEndController extends Controller
{
    /**
     * Enforce middleware.
     */
    public function __construct()
    {
        $this->middleware('auth:profile', ['only' => ['bid','buynow']]);
        if(isset($_SERVER['HTTP_REFERER'])){
            $referral = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST);
            if ($referral != $_SERVER['SERVER_NAME']){

                $brwsr = Counter::where('type','browser')->where('referral',$this->getOS());
                if($brwsr->count() > 0){
                    $brwsr = $brwsr->first();
                    $tbrwsr['total_count']= $brwsr->total_count + 1;
                    $brwsr->update($tbrwsr);
                }else{
                    $newbrws = new Counter();
                    $newbrws['referral']= $this->getOS();
                    $newbrws['type']= "browser";
                    $newbrws['total_count']= 1;
                    $newbrws->save();
                }

                $count = Counter::where('referral',$referral);
                if($count->count() > 0){
                    $counts = $count->first();
                    $tcount['total_count']= $counts->total_count + 1;
                    $counts->update($tcount);
                }else{
                    $newcount = new Counter();
                    $newcount['referral']= $referral;
                    $newcount['total_count']= 1;
                    $newcount->save();
                }
            }
        }else{
            $brwsr = Counter::where('type','browser')->where('referral',$this->getOS());
            if($brwsr->count() > 0){
                $brwsr = $brwsr->first();
                $tbrwsr['total_count']= $brwsr->total_count + 1;
                $brwsr->update($tbrwsr);
            }else{
                $newbrws = new Counter();
                $newbrws['referral']= $this->getOS();
                $newbrws['type']= "browser";
                $newbrws['total_count']= 1;
                $newbrws->save();
            }
        }
    }



    function getOS() {

        $user_agent     =   $_SERVER['HTTP_USER_AGENT'];

        $os_platform    =   "Unknown OS Platform";

        $os_array       =   array(
            '/windows nt 10/i'     =>  'Windows 10',
            '/windows nt 6.3/i'     =>  'Windows 8.1',
            '/windows nt 6.2/i'     =>  'Windows 8',
            '/windows nt 6.1/i'     =>  'Windows 7',
            '/windows nt 6.0/i'     =>  'Windows Vista',
            '/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
            '/windows nt 5.1/i'     =>  'Windows XP',
            '/windows xp/i'         =>  'Windows XP',
            '/windows nt 5.0/i'     =>  'Windows 2000',
            '/windows me/i'         =>  'Windows ME',
            '/win98/i'              =>  'Windows 98',
            '/win95/i'              =>  'Windows 95',
            '/win16/i'              =>  'Windows 3.11',
            '/macintosh|mac os x/i' =>  'Mac OS X',
            '/mac_powerpc/i'        =>  'Mac OS 9',
            '/linux/i'              =>  'Linux',
            '/ubuntu/i'             =>  'Ubuntu',
            '/iphone/i'             =>  'iPhone',
            '/ipod/i'               =>  'iPod',
            '/ipad/i'               =>  'iPad',
            '/android/i'            =>  'Android',
            '/blackberry/i'         =>  'BlackBerry',
            '/webos/i'              =>  'Mobile'
        );

        foreach ($os_array as $regex => $value) {

            if (preg_match($regex, $user_agent)) {
                $os_platform    =   $value;
            }

        }
        return $os_platform;
    }




    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $languages = SectionTitles::findOrFail(1);
        $services = ServiceSection::all();
        $categories = Category::orderBy('name','asc')->get();
        $portfilos = Portfolio::all();
        $fauctions = Auction::where('featured',1)
            ->where('admin_aproved','yes')
            ->where('status','open')
            ->orderBy('id', 'desc')
            ->take(6)
            ->get();
        $newauctions = Auction::where('status','open')
            ->where('admin_aproved','yes')
            ->orderBy('id', 'desc')
            ->take(6)
            ->get();
        $testimonials = Testimonial::all();
        return view('index', compact('services','categories','portfilos','testimonials','fauctions','newauctions','languages'));
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

    //Package Data
    public function viewcampaign($id)
    {
        $campaign = Campaign::findOrFail($id);

        $donations = Donation::where('campid',$id)->
            where('status','Completed')->sum('amount');
        $percent = (100/$campaign->goal) * $donations;

        $gallery = Gallery::where('packageid',$id)->get();
        return view('campaigndetails',compact('campaign','donations','percent'));
    }

    //Auction Details
    public function viewauction($id)
    {
        $auction = Auction::findOrFail($id);
	$popauctions = Auction::where('category',$auction->category)->where('status','open')->take(6)->get();
        $recentbids = Bid::where('auctionid',$id)->orderBy('updated_at','desc')->take(5)->get();
        $bids = Bid::where('auctionid',$id)->orderBy('updated_at','desc')->get();

        $gallerys = AuctionGallery::where('auctionid',$id)->get();
        return view('auctiondetails',compact('auction','bids','recentbids','donations','gallerys','popauctions'));
    }

    //Bid on Auction
    public function bid($id)
    {
        $auct = Auction::findOrFail($id);
        $bidamount = Input::get('amount');
        if ($auct->start_amount <= $bidamount){
            $bid = Bid::where('auctionid', $id)->max('bid_amount');
            if ($bid > $bidamount){
                return redirect('auction/'.$id)->with('error','Your Bid Amount is Lower than highest Bid.');
            }else{
                if (Bid::where('bidder', Auth::user()->id)->where('auctionid', $id)->count() > 0){
                    return redirect('auction/'.$id)->with('error','You already Bid for this Auction.');
                }
                $newbid = new Bid();
                $newbid['bidder'] = Auth::user()->id;
                $newbid['auctionid'] = $id;
                $newbid['bid_amount'] = $bidamount;
                $newbid->save();
                return redirect('auction/'.$id)->with('message','Your Bid Placed Successfully.');
            }
        }else{
            return redirect('auction/'.$id)->with('error','Minimum Bid Amount is: $'.$auct->start_amount);
        }
    }


    //Show Order Form
    public function buynow($id)
    {
        $auction = Auction::findOrFail($id);

        return view('buynow', compact('auction'));
    }


    //Contact Page Data
    public function contact()
    {
        $pagedata = PageSettings::find(1);
        return view('contact', compact('pagedata'));
    }

    //About Page Data
    public function about()
    {
        $pagedata = PageSettings::find(1);
        return view('about', compact('pagedata'));
    }

    //About Page Data
    public function stripe()
    {
        return view('stripe');
    }

    //FAQ Page Data
    public function faq()
    {
        $pagedata = PageSettings::find(1);
        return view('faq', compact('pagedata'));
    }

    //Show All Users
    public function all()
    {
        $cities = UsersModel::distinct()->get(['city']);
        $categories = Category::all();
        $allusers = UsersModel::where('status', 1)->get();
        $pagename = "All Lawyers List";
        return view('listall', compact('allusers','pagename','categories','cities'));
    }

    //Show Featured Auctions
    public function featured()
    {
        $auctions = Auction::where('featured',1)
            ->where('admin_aproved','yes')
            ->where('status','open')
            ->orderBy('id', 'desc')
            ->take(6)
            ->get();
        $pagename = "Featured Auctions";
        return view('featuredauctions', compact('auctions','pagename'));
    }

    //Show Category Auctions
    public function category($category)
    {
        $categories = Category::where('slug',$category)->first();
        $auctions = Auction::where('status', 'open')
            ->where('category', $categories->name)
            ->get();
        $pagename = "All Auctions in: ".$categories->name;
        return view('categoryauctions', compact('auctions','pagename'));
    }

    //Show All Packages
    public function campaigns()
    {
        $campaigns = Campaign::where('status','running')
                ->where('admin_aproved','yes')
                ->orderBy('id','desc')
                ->get();
        return view('allcampaign', compact('campaigns'));
    }

    //Show Order Form
    public function donate(Request $request, $id)
    {
        if (isset($request)) {
            session(['amount' => $request->amount]);
        }

        $campaign = Campaign::where('status', 'running')
            ->where('id', $id)
            ->first();

        return view('donate', compact('campaign','data'));
    }

    //Show Searched Users
    public function donates($id)
    {
        $campaign = Campaign::where('status', 'running')
            ->where('id', $id)
            ->first();

        return view('donate', compact('campaign','data'));
    }

    //User Subscription
    public function subscribe(Request $request)
    {
        $subscribe = new Subscribers;
        $subscribe->fill($request->all());
        $subscribe->save();
        Session::flash('subscribe', 'You are subscribed Successfully.');
        return redirect('/');
    }

    //Send email to user
    public function usermail(Request $request)
    {
        $settings = Settings::findOrFail(1);
        $pagedata = PageSettings::findOrFail(1);
        $subject = "Contact From Of ".$settings->title;
        $to = $pagedata->contact_email;
        $name = $request->name;
        $from = $request->email;
        $msg = "Name: ".$name."\nEmail: ".$from."\nMessage: ".$request->message;

        Session::flash('pmail', 'Thank you for Contacting !!');
        mail($to,$subject,$msg);

        return redirect('/package/'.$request->pid);
    }
    
    //Send email to Admin
    public function contactmail(Request $request)
    {
        $settings = Settings::findOrFail(1);
        $pagedata = PageSettings::findOrFail(1);
        $subject = "Contact From Of ".$settings->title;
        $to = $request->to;
        $name = $request->name;
        $phone = $request->phone;
        $department = $request->department;
        $from = $request->email;
        $msg = "Name: ".$name."\nEmail: ".$from."\nPhone: ".$request->phone."\nGender ".$request->department."\nMessage: ".$request->message;

        mail($to,$subject,$msg);

        Session::flash('cmail', $pagedata->contact);
        return redirect('/contact');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
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
