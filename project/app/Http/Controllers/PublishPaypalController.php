<?php

namespace App\Http\Controllers;

use App\Auction;
use App\Order;
use App\Package;
use App\PricingTable;
use App\Settings;
use App\Transaction;
use Illuminate\Http\Request;

class PublishPaypalController extends Controller
{


 public function store(Request $request){


     $auction = Auction::findOrFail($request->auction);

     $transaction = new Transaction();
     
     $settings = Settings::findOrFail(1);

     $paypal_email = $settings->paypal_business;
     $return_url = action('PublishPaypalController@payreturn');
     $cancel_url = action('PublishPaypalController@paycancle');
     $notify_url = action('PublishPaypalController@notify');

     $item_name = $auction->title." Listing Payment";
     $item_number = str_random(2).time();
     $item_amount = $request->amount;

     $querystring = '';

     // Firstly Append paypal account to querystring
     $querystring .= "?business=".urlencode($paypal_email)."&";

     // Append amount& currency (£) to quersytring so it cannot be edited in html

     //The item name and amount can be brought in dynamically by querying the $_POST['item_number'] variable.
     $querystring .= "item_name=".urlencode($item_name)."&";
     $querystring .= "amount=".urlencode($item_amount)."&";
     $querystring .= "item_number=".urlencode($item_number)."&";

    $querystring .= "cmd=".urlencode(stripslashes($request->cmd))."&";
    $querystring .= "bn=".urlencode(stripslashes($request->bn))."&";
    $querystring .= "lc=".urlencode(stripslashes($request->lc))."&";
    $querystring .= "currency_code=".urlencode(stripslashes($request->currency_code))."&";

     // Append paypal return addresses
     $querystring .= "return=".urlencode(stripslashes($return_url))."&";
     $querystring .= "cancel_return=".urlencode(stripslashes($cancel_url))."&";
     $querystring .= "notify_url=".urlencode($notify_url)."&";

     $querystring .= "custom=".$request->auction;

		$transaction['transaction'] = $item_number;
		$transaction['userid'] = $request->userid;
		$transaction['auctionid'] = $request->auction;
		$transaction['amount'] = $item_amount;
		$transaction['reason'] = "listing";
		$transaction['address'] = $request->address;
		$transaction['city'] = $request->city;
		$transaction['zip'] = $request->zip;
		$transaction['featured'] = $request->featured;
		$transaction['method'] = $request->methods;
		$transaction['payment_status'] = "Pending";
		$transaction['status'] = "pending";
		$transaction->save();
		
			if ($request->featured == "yes") {
			$datas['featured'] = 1;
			}
		$auction->update($datas);

        // Redirect to paypal IPN
         header('location:https://www.paypal.com/cgi-bin/webscr'.$querystring);
         exit();

 }


 public function paycancle(){
     return redirect()->back();
 }

public function payreturn(){
    return redirect('user/auction')->with('message','Payment Completed. Your Auction Published Successfully.');
 }

public function notify(Request $request){

    $raw_post_data = file_get_contents('php://input');
    $raw_post_array = explode('&', $raw_post_data);
    $myPost = array();
    foreach ($raw_post_array as $keyval) {
        $keyval = explode ('=', $keyval);
        if (count($keyval) == 2)
            $myPost[$keyval[0]] = urldecode($keyval[1]);
    }

// Read the post from PayPal system and add 'cmd'
    $req = 'cmd=_notify-validate';
    if(function_exists('get_magic_quotes_gpc')) {
        $get_magic_quotes_exists = true;
    }
    foreach ($myPost as $key => $value) {
        if($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1) {
            $value = urlencode(stripslashes($value));
        } else {
            $value = urlencode($value);
        }
        $req .= "&$key=$value";
    }

    /*
     * Post IPN data back to PayPal to validate the IPN data is genuine
     * Without this step anyone can fake IPN data
     */
    $paypalURL = "https://www.paypal.com/cgi-bin/webscr";
    $ch = curl_init($paypalURL);
    if ($ch == FALSE) {
        return FALSE;
    }
    curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
    curl_setopt($ch, CURLOPT_SSLVERSION, 6);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);

// Set TCP timeout to 30 seconds
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close', 'User-Agent: company-name'));
    $res = curl_exec($ch);

    /*
     * Inspect IPN validation result and act accordingly
     * Split response headers and payload, a better way for strcmp
     */
    $tokens = explode("\r\n\r\n", trim($res));
    $res = trim(end($tokens));
    if (strcmp($res, "VERIFIED") == 0 || strcasecmp($res, "VERIFIED") == 0) {

        $transaction = Transaction::where('auctionid',$_POST['custom'])
            ->where('transaction',$_POST['item_number']);
	        $data['txn_id'] = $_POST['txn_id'];
		$data['status'] = "completed";
	        $data['payment_status'] = $_POST['payment_status'];
        $transaction->update($data);

        $auction = Auction::findOrFail($_POST['custom']);
		$datas['admin_aproved'] = 'yes';
		$datas['status'] = 'open';
	$auction->update($datas);


//
//        $fh = fopen('paymentLaravel.txt', 'w');
//        fwrite($fh, $req);
//        fclose($fh);
//
//
//        $fs = fopen('paymentstatus.txt', 'w');
//        fwrite($fs, $_POST['payment_status']);
//        fclose($fs);
//        //return "yes";

    }else{

        $fh = fopen('newresag.txt', 'w');
        fwrite($fh, $req);
        fclose($fh);
    }

}



}
