@extends('user.includes.master-user')

@section('content')

    <div id="page-wrapper">
        <div class="container-fluid">
            <div class="row" id="main">

                <!-- Page Heading -->
                <div class="go-title">
                    <div class="pull-right">
                        <a href="{!! url('user/mybids') !!}" class="btn btn-default btn-back"><i class="fa fa-arrow-left"></i> Back</a>
                    </div>
                    <h3>Bid Winner's Pay</h3>
                    <div class="go-line"></div>
                </div>
                <!-- Page Content -->
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div id="response">
                            @if(Session::has('error'))
                                <div class="alert alert-danger alert-dismissable">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                    {{ Session::get('error') }}
                                </div>
                            @endif
                        </div>
                        <table class="table">
                            <tbody>
                            <tr>
                                <td width="30%" style="text-align: right;"><strong>Auction Title</strong></td>
                                <td><a href="{{url('/auction/'.$auction->id)}}" target="_blank">{{$auction->title}}</a></td>
                            </tr>
                            <tr>
                                <td width="30%" style="text-align: right;"><strong>Auction Status:</strong></td>
                                <td>{{ucfirst($auction->status)}}</td>
                            </tr>
                            <tr>
                                <td width="30%" style="text-align: right;"><strong>End Date:</strong></td>
                                <td>{{date('jS F Y',strtotime($auction->created_at))}}</td>
                            </tr>
                            <tr>
                                <td width="30%" style="text-align: right;"><strong>Item Price:</strong></td>
                                <td>${{$auction->price}}</td>
                            </tr>
                            <tr>
                                <td width="30%" style="text-align: right;"><strong>Total Bids:</strong></td>
                                <td>{{\App\Bid::countBid($auction->id)}}</td>
                            </tr>
                            <tr>
                                <td width="30%" style="text-align: right;"><strong>Highest Bid:</strong></td>
                                <td>{{\App\Bid::maxBid($auction->id)}}</td>
                            </tr>
                            <tr>
                                <td width="30%" style="text-align: right;"><strong>Your Bid:</strong></td>
                                <td>${{$bid->bid_amount}} <strong style="color: green">(Winner)</strong></td>
                            </tr>
                            <tr>
                                <td width="30%" style="text-align: right;"><strong>Select Payment Method:</strong></td>
                                <td>
                                    <form role="form" method="POST" id="payment_form" action="{{route('winner.pay')}}">
                                        {{ csrf_field() }}
                                        <div class="form-group">
                                            <select class="form-control" onChange="meThods(this)" id="formac" name="methods" required>
                                                <option value="Paypal" selected>Paypal</option>
                                                <option value="Stripe">Credit Card</option>
                                            </select>
                                        </div>
					<label>Enter Shipping Details</label>
					<div class="form-group">
						<input type="text" class="form-control" name="address" placeholder="Shipping Address" required>
					</div>
					<div class="form-group">
						<input type="text" class="form-control" name="city" placeholder="City" required>
					</div>
					<div class="form-group">
						<input type="text" class="form-control" name="zip" placeholder="Postal Code" required>
					</div>
                                            
                                        {{--<div class="form-group">--}}
                                        {{--<input type="text" class="form-control" name="total" placeholder="Deposit Amount" required>--}}
                                        {{--</div>--}}

                                        <div id="stripes" style="display: none;">
					<label>Enter Credit Card Information</label>
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="card" placeholder="Card Number">
                                            </div>
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="cvv" placeholder="CVV">
                                            </div>
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="month" placeholder="Month">
                                            </div>
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="year" placeholder="Year">
                                            </div>
                                        </div>

                                        <input type="hidden" name="userid" value="{{Auth::user()->id}}" />
                                        <input type="hidden" name="bidid" value="{{$auction->winner}}" />

                                        <div id="paypals">
                                            <input type="hidden" name="cmd" value="_xclick" />
                                            <input type="hidden" name="no_note" value="1" />
                                            <input type="hidden" name="lc" value="UK" />
                                            <input type="hidden" name="currency_code" value="USD" />
                                            <input type="hidden" name="bn" value="PP-BuyNowBF:btn_buynow_LG.gif:NonHostedGuest" />
                                        </div>

                                        <!-- Button -->
                                        <div class="form-group">
                                            <label class="col-md-4 control-label"></label>
                                            <div class="col-md-4">
                                                <button type="submit" class="btn btn-success btn-block"><strong>Pay Now</strong></button>
                                            </div>
                                        </div>
                                    </form>
                                </td>
                            </tr>
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </div>
    <!-- /#page-wrapper -->
@stop

@section('footer')
    <script>

        function meThods(val) {
            var action1 = "{{route('winner.pay')}}";
            var action2 = "{{route('stripe.winner')}}";
            if (val.value == "Paypal") {
                $("#payment_form").attr("action", action1);
                $("#stripes").hide();
            }
            if (val.value == "Stripe") {
                $("#payment_form").attr("action", action2);
                $("#stripes").show();
            }
        }
    </script>
@stop