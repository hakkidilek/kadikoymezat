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
                                    <input type="text" class="form-control" name="name" placeholder="Full Name" required>
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="phone" placeholder="Phone Number(Optional)">
                                </div>
                                <div class="form-group">
                                    <input type="email" class="form-control" name="email" placeholder="Email" required>
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="address" placeholder="Address(Optional)">
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="city" placeholder="City(Optional)">
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="zip" placeholder="Postal Code(Optional)">
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
                                    <input type="hidden" name="amount" value="{{ Session::get('amount') }}" />
                                    <input type="hidden" name="campaign" value="{{$campaign->id}}" />
                                    <input type="hidden" name="bn" value="PP-BuyNowBF:btn_buynow_LG.gif:NonHostedGuest" />
                                </div>
                                <button type="submit" class="genius-btn"></i> Donate Now</button>
                            </form>

                        </div>
                        <div class="col-md-4 order-right">
                            <h4>CAMPAIGN DETAILS</h4>
                            <h3>{{$campaign->title}}</h3>
                            <h5>Campaign Goal: ${{$campaign->goal}}</h5>
                            <h5>Total Funded: ${{\App\Donation::getFund($campaign->id)}}</h5>
                            <hr>
                            <div class="pricing-list">
                                <p class="pricing-count">My Donate Amount: ${{ Session::get('amount') }}</p>
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