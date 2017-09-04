<?php

namespace App\Http\Controllers;

use App\Auction;
use App\Bid;
use App\Transaction;
use Illuminate\Http\Request;
use URL;
use Redirect;
use Input;
use Validator;
use App\Order;
use App\Package;
use App\PricingTable;
use App\Settings;
use Config;

use Cartalyst\Stripe\Laravel\Facades\Stripe;
use Stripe\Error\Card;

class StripeController extends Controller
{

    public function __construct()
    {
        //Set Spripe Keys
        $stripe = Settings::findOrFail(1);
  		Config::set('services.stripe.key', $stripe->stripe_key);
  		Config::set('services.stripe.secret', $stripe->stripe_secret);
    }


    public function store(Request $request){

        $auction = Auction::findOrFail($request->auction);

        $transaction = new Transaction();
		$success_url = action('PaymentController@payreturn');
		$item_name = $auction->title." Buy Payment";
     		$item_number = str_random(2).time();
		$item_amount = $auction->price;

		$validator = Validator::make($request->all(),[
						'card' => 'required',
						'cvv' => 'required',
						'month' => 'required',
						'year' => 'required',
					]);

		if ($validator->passes()) {

	     	$stripe = Stripe::make(Config::get('services.stripe.secret'));
	     	try{
	     		$token = $stripe->tokens()->create([
	     			'card' =>[
	     					'number' => $request->card,
	     					'exp_month' => $request->month,
	     					'exp_year' => $request->year,
	     					'cvc' => $request->cvv,
	     				],
	     			]);
	     		if (!isset($token['id'])) {
	     			return back()->with('error','Token Problem With Your Token.');
	     		}

	     		$charge = $stripe->charges()->create([
	     			'card' => $token['id'],
	     			'currency' => 'USD',
	     			'amount' => $item_amount,
	     			'description' => $item_name,
	     			]);

	     		//dd($charge);

	     		if ($charge['status'] == 'succeeded') {

                    $transaction['transaction'] = $item_number;
                    $transaction['userid'] = $request->userid;
                    $transaction['auctionid'] = $request->auction;
                    $transaction['amount'] = $item_amount;
                    $transaction['reason'] = "buy";
                    $transaction['address'] = $request->address;
                    $transaction['city'] = $request->city;
                    $transaction['zip'] = $request->zip;
                    $transaction['method'] = $request->methods;
                    $transaction['payment_status'] = "Completed";
                    $transaction['txn_id'] = $charge['balance_transaction'];
                    $transaction['charge_id'] = $charge['id'];
                    $transaction['status'] = "completed";

                    $transaction->save();
                    
                    	$bid = new Bid();
                    	$bid['bidder'] = $request->userid;
			$bid['auctionid'] = $request->auction;
			$bid['bid_amount'] = $auction->price;
			$bid['winner'] = "yes";
                    	$bid->save();

                    $data['winner'] = $bid->id;
                    $data['paid_status'] = "yes";
                    $data['end_date'] = date('Y-m-d');
                    $data['status'] = 'closed';
                    $auction->update($data);

	     			return redirect($success_url);
	     		}
	     		
	     	}catch (Exception $e){
	     		return back()->with('error', $e->getMessage());
	     	}catch (\Cartalyst\Stripe\Exception\CardErrorException $e){
	     		return back()->with('error', $e->getMessage());
	     	}catch (\Cartalyst\Stripe\Exception\MissingParameterException $e){
	     		return back()->with('error', $e->getMessage());
	     	}
		}
		return back()->with('error', 'Please Enter Valid Credit Card Informations.');
	}
}
