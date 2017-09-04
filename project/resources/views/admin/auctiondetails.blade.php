@extends('admin.includes.master-admin')

@section('content')

    <div id="page-wrapper">
        <div class="container-fluid">
            <div class="row" id="main">
                <!-- Page Heading -->
                <div class="go-title">
                    <div class="pull-right">
                        <a href="{!! url('admin/auction') !!}" class="btn btn-default btn-add"><i class="fa fa-arrow-left"></i> Back</a>
                    </div>
                    <h3>Auction Details</h3>
                    
                </div>
                <!-- Page Content -->
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="col-md-12">
                            <ul class="nav nav-tabs tabs-left">
                                <li class="active"><a href="#campdetails" data-toggle="tab" aria-expanded="false"><strong>Auction Details</strong></a>
                                <li><a href="#bids" data-toggle="tab" aria-expanded="true"><strong>Bid Information</strong></a>
                                </li>
                            </ul>
                        </div>

                        <div class="col-xs-12" style="padding: 0">
                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div class="tab-pane active" id="campdetails">
                                    <div class="go-title">
                                        <h4>{{$auction->title}}</h4>
                                        <div class="go-line"></div>
                                    </div>
                        <table class="table">
                            <tbody>
                                <tr>
                                    <td width="30%" style="text-align: right;"><strong>Auction ID#</strong></td>
                                    <td>{{$auction->id}}</td>
                                </tr>
                                <tr>
                                    <td width="30%" style="text-align: right;"><strong>Auction Status:</strong></td>
                                    <td>{{ucfirst($auction->status)}}</td>
                                </tr>
                                <tr>
                                    <td width="30%" style="text-align: right;"><strong>Listing Type:</strong></td>
                                    <td>
                                        @if($auction->featured == 1)
                                            <label class="label label-primary">Featured</label>
                                        @else
                                            <label class="label label-default">Basic</label>
                                        @endif
                                    </td>
                                </tr>

                                @if($auction->winner != "")
                                    <tr class="success">
                                        <td width="30%" style="text-align: right;"><strong> Winner: </strong></td>
                                        <td>{{\App\Bid::findOrFail($auction->winner)->bidder->name}}</td>
                                    </tr>
                                    <tr class="success">
                                        <td width="30%" style="text-align: right;"><strong> Winning Amount: </strong></td>
                                        <td>${{\App\Bid::findOrFail($auction->winner)->bid_amount}}</td>
                                    </tr>
                                    <tr class="success">
                                        <td width="30%" style="text-align: right;"><strong> Shipping </strong></td>
                                        <td>
                                        	@if(\App\Transaction::where('auctionid',$auction->id)->where('userid',\App\Bid::findOrFail($auction->winner)->bidder->id)->where('reason','buy')->where('payment_status','Completed')->count() > 0)
                                        		<strong>Address:</strong> {{\App\Transaction::where('auctionid',$auction->id)->where('userid',\App\Bid::findOrFail($auction->winner)->bidder->id)->where('reason','buy')->where('payment_status','Completed')->first()->address}}<br>
                                        		<strong>City:</strong> {{\App\Transaction::where('auctionid',$auction->id)->where('userid',\App\Bid::findOrFail($auction->winner)->bidder->id)->where('reason','buy')->where('payment_status','Completed')->first()->city}}<br>
                                        		<strong>Zip:</strong> {{\App\Transaction::where('auctionid',$auction->id)->where('userid',\App\Bid::findOrFail($auction->winner)->bidder->id)->where('reason','buy')->where('payment_status','Completed')->first()->zip}}<br>
                                        	@else
                                        		<label class="label label-danger">Unpaid</label>
                                        	@endif
                                        </td>
                                    </tr>
                                @endif
                                <tr>
                                    <td width="30%" style="text-align: right;"><strong>Created On:</strong></td>
                                    <td>{{date('Y-m-d h:i:sa',strtotime($auction->created_at))}}</td>
                                </tr>
                                <tr>
                                    <td width="30%" style="text-align: right;"><strong>End Date:</strong></td>
                                    <td>{{date('Y-m-d h:i:sa',strtotime($auction->end_date))}}</td>
                                </tr>
                                <tr>
                                    <td width="30%" style="text-align: right;"><strong>Created By:</strong></td>
                                    <td>{{$auction->createdby->name}}</td>
                                </tr>
                                <tr>
                                    <td width="30%" style="text-align: right;"><strong>Auction Title:</strong></td>
                                    <td>{{$auction->title}}</td>
                                </tr>
                                <tr>
                                    <td width="30%" style="text-align: right;"><strong>Auction Category:</strong></td>
                                    <td>{{$auction->category}}</td>
                                </tr>
                                <tr>
                                    <td width="30%" style="text-align: right;"><strong>Item Condition:</strong></td>
                                    <td>{{$auction->condition}}</td>
                                </tr>

                                <tr>
                                    <td width="30%" style="text-align: right;"><strong>Buy Now Price:</strong></td>
                                    <td>${{$auction->price}}</td>
                                </tr>
                                <tr>
                                    <td width="30%" style="text-align: right;"><strong>Auction Start Amount:</strong></td>
                                    <td>${{$auction->start_amount}}</td>
                                </tr>
                                <tr>
                                    <td width="30%" style="text-align: right;"><strong>Feature Image:</strong></td>
                                    <td><img style="max-width: 300px;" src="{{url('assets/images/auction')}}/{{$auction->feature_image}}" ></td>
                                </tr>
                                <tr>
                                    <td width="30%" style="text-align: right;"><strong>Auction Description:</strong></td>
                                    <td>{!! $auction->description !!}</td>
                                </tr>

                            </tbody>
                        </table>

                        </div>
                        <div class="tab-pane" id="bids">
                            <div class="go-title">
                                <h4>Total Bids: <strong>{{\App\Bid::countBid($auction->id)}}</strong></h4>
                                <h4>Highest Bid: <strong>{{\App\Bid::maxBid($auction->id)}}</strong></h4>
                                <div class="go-line"></div>
                            </div>
                            <div class="col-md-12">

                                <table class="table">
                                    <thead>
                                    <tr style="background: #222;color: #FFFFFF;">
                                        <th>Bidder</th>
                                        <th>Bid Amount</th>
                                        <th>Date</th>
                                        <th>Contact</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($bids as $bid)
                                        @if($auction->winner == $bid->id)
                                            <tr class="success">
                                        @else
                                            <tr>
                                                @endif
                                                <td>{{$bid->bidder->name}}</td>
                                                <td>${{$bid->bid_amount}}
                                                    @if($auction->winner == $bid->id)
                                                        @if(\App\Auction::findOrFail($bid->auctionid->id)->paid_status == "yes")

                                                            <label class="label label-success">Paid</label>
                                                        @else
                                                            <label class="label label-danger">Unpaid</label>
                                                        @endif

                                                    @endif
                                                </td>
                                                <td>{{$bid->created_at}}</td>
                                                <td>        <a href="{{url('admin/auction/'.$bid->id.'/email')}}" class="btn btn-primary btn-xs">Contact Now</a>
                                                </td>
                                                <td>
                                                    @if($bid->auctionid->winner == "")
                                                        <a href="{{url('admin/auction/'.$bid->id.'/winner/'.$auction->id)}}" class="btn btn-success btn-xs">Make Winner</a>
                                                    @else
                                                        @if($auction->winner == $bid->id)
                                                            <strong>Winner</strong> (<a href="{{url('admin/auction/'.$auction->id.'/cwinner')}}" style="color: red;">Remove</a>)
                                                        @else
                                                            Auction Completed
                                                        @endif
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
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

@stop