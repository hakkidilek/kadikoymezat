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
                    <h3>Edit My Bid</h3>
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
                                <td width="30%" style="text-align: right;"><strong>Total Bid:</strong></td>
                                <td>{{\App\Bid::countBid($auction->id)}}</td>
                            </tr>
                            <tr>
                                <td width="30%" style="text-align: right;"><strong>Highest Bid:</strong></td>
                                <td>{{\App\Bid::maxBid($auction->id)}}</td>
                            </tr>
                            <tr>
                                <td width="30%" style="text-align: right;"><strong>Your Current Bid:</strong></td>
                                <td>${{$bid->bid_amount}}</td>
                            </tr>
                            </tbody>
                        </table>
                        <form method="POST" action="{!! action('UserBidController@update',['id' => $bid->id]) !!}" class="form-horizontal form-label-left">
                            {{csrf_field()}}
                            <input type="hidden" name="_method" value="PATCH">
                            <div class="item form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Place Your New Bid($)<span class="required">*</span>
                                    <p class="small-label">(In USD)</p>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input id="name" class="form-control col-md-7 col-xs-12" name="bid_amount" value="{{$bid->bid_amount}}" placeholder="e.g Sports" required="required" type="number">
                                </div>
                            </div>
                            <div class="ln_solid"></div>
                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-3">
                                    <button type="submit" class="btn btn-success btn-block">Update My Bid</button>
                                </div>
                            </div>
                        </form>
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

@stop