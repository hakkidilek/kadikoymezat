@extends('user.includes.master-user')

@section('content')

    <div id="page-wrapper">
        <div class="container-fluid">
            <div class="row" id="main">
                <!-- Page Heading -->
                <div class="go-title">

                    <h3>My Bids</h3>
                    <div class="go-line"></div>
                </div>
                <!-- Page Content -->
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div id="response">
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
                        <table class="table table-striped table-bordered" cellspacing="0" id="example" width="100%">
                            <thead>
                            <tr>
                                <th>Date</th>
                                <th>Auction</th>
                                <th>Bid Amount</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($bids as $bid)
                                    <tr>
                                        <td>{{$bid->created_at}}</td>
                                        <td>{{$bid->auctionid->title}}</td>
                                        <td>${{$bid->bid_amount}}</td>
                                        <td>
                                            @if($bid->auctionid->status != "open")
                                                @if($bid->winner == "yes")
                                                    <strong>(Winner)</strong>
                                                    @if(\App\Auction::findOrFail($bid->auctionid->id)->paid_status == "no")
                                                    <a href="{{url('user/winner/'.$bid->id.'/pay')}}" class="btn btn-primary btn-xs">Pay Now</a>
                                                    @else
                                                        <label class="label label-success">Paid</label>
                                                    @endif
                                                @else
                                                    Auction Completed
                                                @endif
                                            @else
                                            <a href="mybids/{{$bid->id}}/edit" class="btn btn-primary btn-xs"><i class="fa fa-edit"></i> Edit </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
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

@stop