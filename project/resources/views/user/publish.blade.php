@extends('user.includes.master-user')

@section('content')
    <div id="page-wrapper">
        <div class="container-fluid">
            <div class="row" id="main">

                <!-- Page Heading -->
                <div class="go-title">
                    <div class="pull-right">
                        <a href="{!! url('user/auction') !!}" class="btn btn-default btn-back"><i class="fa fa-arrow-left"></i> Back</a>
                    </div>
                    <h3>Publish Your Auction</h3>
                    <div class="go-line"></div>
                </div>
                <!-- Page Content -->
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div id="resp">
                            @if(Session::has('message'))
                                <div class="alert alert-success alert-dismissable">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                    {{ Session::get('message') }}
                                </div>
                            @endif
                            @if(Session::has('error'))
                                <div class="alert alert-danger alert-dismissable">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                    {{ Session::get('error') }}
                                </div>
                            @endif
                        </div>
                        <div class="col-md-6 col-md-offset-3">

                        <form role="form" method="POST" id="payment_form" action="{{route('user.auction.publish')}}">
                        {{ csrf_field() }}

                            <div class="form-group">
                                <select class="form-control" onChange="opTion(this)" id="formac" name="amount" required>
                                    <option value="">Selecte Publish Option</option>
                                    @if($auction->status == "pending")
                                        <option value="{{$settings[0]->basic_charge}}">Basic - ${{$settings[0]->basic_charge}}</option>
                                    @endif
                                        <option value="{{$settings[0]->featured_charge}}">Featured - ${{$settings[0]->featured_charge}}</option>
                                </select>
                            </div>
                        <div id="withpay" style="display: none">

                                <div class="form-group">
                                    <select class="form-control" onChange="meThods(this)" id="formac" name="methods" required>
                                        <option value="Paypal" selected>Paypal</option>
                                        <option value="Stripe">Credit Card</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <input type="hidden" id="feat" class="form-control" name="featured" value="no">
                                </div>

                                <div id="stripes" style="display: none;">
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
                                <input type="hidden" name="auction" value="{{$auction->id}}" />

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
                        </div>
                        <div id="withoutpay">
                            <div class="form-group">
                                <label class="col-md-4 control-label"></label>
                                <div class="col-md-4">
                                    <button type="submit" class="btn btn-success btn-block"><strong>Publish Now</strong></button>
                                </div>
                            </div>
                        </div>
                            </form>
                    </div>
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
        function opTion(val) {
            var action = "{{route('user.auction.publish')}}";
            var action1 = "{{route('paypal.publish')}}";
            var fe = val.options[val.selectedIndex].text;
            if(fe.match(/featured/gi)){
                $("#feat").val("yes");
            }
            if (val.value > 0) {
                $("#payment_form").attr("action", action1);
                $("#withpay").show();
                $("#withoutpay").hide();
            }else{
                $("#payment_form").attr("action", action);
                $("#withpay").hide();
                $("#withoutpay").show();
            }
        }
        function meThods(val) {
            var action1 = "{{route('paypal.publish')}}";
            var action2 = "{{route('stripe.publish')}}";
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