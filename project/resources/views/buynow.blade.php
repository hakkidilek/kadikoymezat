@extends('includes.master')
@section('content')

    <section class="go-section">
        <div class="row">
            <div class="container">
                <div class="col-md-offset-2 col-md-8">
                @if(Session::has('error'))
                    <div class="alert alert-danger alert-dismissable">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        {{ Session::get('error') }}
                    </div>
                @endif
                </div>
                <div class="col-md-12 text-center services">
                    <div class="col-md-offset-2 col-md-8 order-div">

                        <div class="col-md-8 order-left">
                            <h4>ENTER YOUR DETAILS</h4>
                            <form action="{{route('payment.submit')}}" method="post" id="payment_form">
                                {{csrf_field()}}

                                <div class="form-group">
                                    <input type="text" class="form-control" name="name" value="{{Auth::guard('profile')->user()->name}}" placeholder="Full Name" required>
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" value="{{Auth::guard('profile')->user()->phone}}" name="phone" placeholder="Phone Number(Optional)">
                                </div>
                                <div class="form-group">
                                    <input type="email" class="form-control" value="{{Auth::guard('profile')->user()->email}}" name="email" placeholder="Email" required>
                                </div>
                               
                                <div class="form-group">
                                    <label>Shipping Details</label>
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="address" placeholder="Shipping Address" required>
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="city" placeholder="City" required>
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="zip" placeholder="Postal Code" required>
                                </div>
                                <div class="form-group">
                                    <select class="form-control" onChange="meThods(this)" id="formac" name="method" required>
                                        <option value="Paypal" selected>Paypal</option>
                                        <option value="Stripe">Credit Card</option>
                                    </select>
                                </div>
                                <div id="stripes" style="display: none;">
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="card" placeholder="Card Number">
                                    </div>
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="cvv" placeholder="Cvv">
                                    </div>
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="month" placeholder="Expire Month">
                                    </div>
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="year" placeholder="Expire Year">
                                    </div>
                                </div>
                                <div id="paypals">
                                    <input type="hidden" name="cmd" value="_xclick" />
                                    <input type="hidden" name="no_note" value="1" />
                                    <input type="hidden" name="lc" value="UK" />
                                    <input type="hidden" name="currency_code" value="USD" />
                                    <input type="hidden" name="amount" value="{{ $auction->price }}" />
                                    <input type="hidden" name="auction" value="{{$auction->id}}" />
                                    <input type="hidden" name="userid" value="{{Auth::guard('profile')->user()->id}}" />
                                    <input type="hidden" name="bn" value="PP-BuyNowBF:btn_buynow_LG.gif:NonHostedGuest" />
                                </div>
                                <button type="submit" class="genius-btn"></i> Buy Now</button>
                            </form>

                        </div>
                        <div class="col-md-4 order-right">
                            <h4>AUCTION ITEM DETAILS</h4>
                            <h3>{{$auction->title}}</h3>
                            <h5>Item Price: ${{$auction->price}}</h5>
                            <h5>Total Bids: {{\App\Bid::countBid($auction->id)}}</h5>
                            <h5>Highest Bid: ${{\App\Bid::maxBid($auction->id)}}</h5>
                            <hr>
                            <div class="pricing-list">
                                <h3 style="padding: 10px;">Buy Amount: ${{$auction->price}}</h3>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

@stop

@section('footer')
<script type="text/javascript">
   function meThods(val) { 
          var action1 = "{{route('payment.submit')}}";
          var action2 = "{{route('stripe.submit')}}";
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